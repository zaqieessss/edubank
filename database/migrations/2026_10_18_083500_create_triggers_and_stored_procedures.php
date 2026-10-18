<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Only execute on MySQL (SQLite in testing does not support MySQL syntax)
        if (DB::getDriverName() !== 'mysql') {
            return;
        }

        // =========================================================================
        // 1. TRIGGER: trg_validate_transaction_rules (BEFORE INSERT ON transactions)
        // Aturan:
        // - Minimal setoran & penarikan adalah Rp 10.000.
        // - Saldo mengendap nasabah setelah penarikan minimal Rp 10.000.
        // =========================================================================
        DB::unprepared('DROP TRIGGER IF EXISTS trg_validate_transaction_rules;');
        DB::unprepared("
            CREATE TRIGGER trg_validate_transaction_rules
            BEFORE INSERT ON transactions
            FOR EACH ROW
            BEGIN
                -- 1. Validasi Minimal Transaksi Rp 10.000
                IF NEW.amount < 10000 THEN
                    SIGNAL SQLSTATE '45000'
                    SET MESSAGE_TEXT = 'Database Constraint: Minimal nominal transaksi setoran dan penarikan adalah Rp 10.000!';
                END IF;

                -- 2. Validasi Saldo Mengendap Minimal Rp 10.000 pada Penarikan
                IF NEW.transaction_type = 'withdrawal' THEN
                    IF NEW.balance_after < 10000 THEN
                        SIGNAL SQLSTATE '45000'
                        SET MESSAGE_TEXT = 'Database Constraint: Penarikan ditolak! Saldo mengendap nasabah setelah penarikan minimal Rp 10.000!';
                    END IF;
                END IF;
            END;
        ");

        // =========================================================================
        // 2. STORED PROCEDURE: sp_process_deposit
        // Menangani setoran tunai secara atomik langsung di level database:
        // - Lock baris saver
        // - Tambah saldo saver
        // - Catat mutasi di tabel transactions
        // - Catat 2 baris jurnal akuntansi double-entry (Debet 101, Kredit 201)
        // =========================================================================
        DB::unprepared('DROP PROCEDURE IF EXISTS sp_process_deposit;');
        DB::unprepared("
            CREATE PROCEDURE sp_process_deposit(
                IN p_saver_id BIGINT UNSIGNED,
                IN p_user_id BIGINT UNSIGNED,
                IN p_amount BIGINT,
                IN p_description VARCHAR(255),
                OUT p_transaction_id BIGINT UNSIGNED
            )
            BEGIN
                DECLARE v_current_balance BIGINT DEFAULT 0;
                DECLARE v_new_balance BIGINT DEFAULT 0;
                DECLARE v_trx_code VARCHAR(50);
                DECLARE v_trx_count INT DEFAULT 0;

                -- Validasi minimal nominal
                IF p_amount < 10000 THEN
                    SIGNAL SQLSTATE '45000'
                    SET MESSAGE_TEXT = 'Minimal setoran adalah Rp 10.000!';
                END IF;

                -- Ambil dan lock saldo nasabah
                SELECT balance INTO v_current_balance
                FROM savers
                WHERE id = p_saver_id
                FOR UPDATE;

                SET v_new_balance = v_current_balance + p_amount;

                -- Update Saldo Savers
                UPDATE savers
                SET balance = v_new_balance, updated_at = NOW()
                WHERE id = p_saver_id;

                -- Generate Kode Transaksi
                SELECT COUNT(*) + 1 INTO v_trx_count FROM transactions;
                SET v_trx_code = CONCAT('TRX-', DATE_FORMAT(NOW(), '%Y%m%d'), '-', LPAD(v_trx_count, 4, '0'));

                -- Insert Transaksi
                INSERT INTO transactions (
                    transaction_code, saver_id, user_id, transaction_type,
                    amount, balance_before, balance_after, description,
                    transaction_date, created_at, updated_at
                ) VALUES (
                    v_trx_code, p_saver_id, p_user_id, 'deposit',
                    p_amount, v_current_balance, v_new_balance,
                    IFNULL(p_description, 'Setoran Tunai via Stored Procedure'),
                    NOW(), NOW(), NOW()
                );

                SET p_transaction_id = LAST_INSERT_ID();

                -- Insert Double-Entry Jurnal
                -- Debet Kas Teller (101)
                INSERT INTO accounting_journals (
                    transaction_id, account_code, account_name,
                    position, amount, entry_date, created_at
                ) VALUES (
                    p_transaction_id, '101', 'Cash at Teller',
                    'debit', p_amount, NOW(), NOW()
                );

                -- Kredit Simpanan Nasabah (201)
                INSERT INTO accounting_journals (
                    transaction_id, account_code, account_name,
                    position, amount, entry_date, created_at
                ) VALUES (
                    p_transaction_id, '201', 'Student Savings Liability',
                    'credit', p_amount, NOW(), NOW()
                );
            END;
        ");

        // =========================================================================
        // 3. STORED PROCEDURE: sp_process_withdrawal
        // Menangani penarikan tunai secara atomik langsung di level database:
        // - Lock baris saver
        // - Validasi saldo mengendap Rp 10.000
        // - Kurangi saldo saver
        // - Catat mutasi di tabel transactions
        // - Catat 2 baris jurnal akuntansi double-entry (Debet 201, Kredit 101)
        // =========================================================================
        DB::unprepared('DROP PROCEDURE IF EXISTS sp_process_withdrawal;');
        DB::unprepared("
            CREATE PROCEDURE sp_process_withdrawal(
                IN p_saver_id BIGINT UNSIGNED,
                IN p_user_id BIGINT UNSIGNED,
                IN p_amount BIGINT,
                IN p_description VARCHAR(255),
                OUT p_transaction_id BIGINT UNSIGNED
            )
            BEGIN
                DECLARE v_current_balance BIGINT DEFAULT 0;
                DECLARE v_new_balance BIGINT DEFAULT 0;
                DECLARE v_trx_code VARCHAR(50);
                DECLARE v_trx_count INT DEFAULT 0;

                -- Validasi minimal nominal
                IF p_amount < 10000 THEN
                    SIGNAL SQLSTATE '45000'
                    SET MESSAGE_TEXT = 'Minimal penarikan adalah Rp 10.000!';
                END IF;

                -- Ambil dan lock saldo nasabah
                SELECT balance INTO v_current_balance
                FROM savers
                WHERE id = p_saver_id
                FOR UPDATE;

                -- Validasi saldo mengendap minimal Rp 10.000
                IF (v_current_balance - p_amount) < 10000 THEN
                    SIGNAL SQLSTATE '45000'
                    SET MESSAGE_TEXT = 'Saldo tidak mencukupi! Penarikan wajib menyisakan saldo mengendap minimal Rp 10.000!';
                END IF;

                SET v_new_balance = v_current_balance - p_amount;

                -- Update Saldo Savers
                UPDATE savers
                SET balance = v_new_balance, updated_at = NOW()
                WHERE id = p_saver_id;

                -- Generate Kode Transaksi
                SELECT COUNT(*) + 1 INTO v_trx_count FROM transactions;
                SET v_trx_code = CONCAT('TRX-', DATE_FORMAT(NOW(), '%Y%m%d'), '-', LPAD(v_trx_count, 4, '0'));

                -- Insert Transaksi
                INSERT INTO transactions (
                    transaction_code, saver_id, user_id, transaction_type,
                    amount, balance_before, balance_after, description,
                    transaction_date, created_at, updated_at
                ) VALUES (
                    v_trx_code, p_saver_id, p_user_id, 'withdrawal',
                    p_amount, v_current_balance, v_new_balance,
                    IFNULL(p_description, 'Penarikan Tunai via Stored Procedure'),
                    NOW(), NOW(), NOW()
                );

                SET p_transaction_id = LAST_INSERT_ID();

                -- Insert Double-Entry Jurnal
                -- Debet Simpanan Nasabah (201)
                INSERT INTO accounting_journals (
                    transaction_id, account_code, account_name,
                    position, amount, entry_date, created_at
                ) VALUES (
                    p_transaction_id, '201', 'Student Savings Liability',
                    'debit', p_amount, NOW(), NOW()
                );

                -- Kredit Kas Teller (101)
                INSERT INTO accounting_journals (
                    transaction_id, account_code, account_name,
                    position, amount, entry_date, created_at
                ) VALUES (
                    p_transaction_id, '101', 'Cash at Teller',
                    'credit', p_amount, NOW(), NOW()
                );
            END;
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (DB::getDriverName() !== 'mysql') {
            return;
        }

        DB::unprepared('DROP TRIGGER IF EXISTS trg_validate_transaction_rules;');
        DB::unprepared('DROP PROCEDURE IF EXISTS sp_process_deposit;');
        DB::unprepared('DROP PROCEDURE IF EXISTS sp_process_withdrawal;');
    }
};
