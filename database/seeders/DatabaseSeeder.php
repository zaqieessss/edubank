<?php

namespace Database\Seeders;

use App\Models\AccountingJournal;
use App\Models\Member;
use App\Models\Saver;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Create Default Admin (Combined Admin & Supervisor)
        $admin = User::create([
            'name' => 'Administrator Bank Mini',
            'email' => 'admin@edubank.test',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'status' => 'active',
        ]);

        // 2. Create Default Teller
        $teller = User::create([
            'name' => 'Piket Teller 1',
            'email' => 'teller@edubank.test',
            'password' => Hash::make('password'),
            'role' => 'teller',
            'status' => 'active',
        ]);

        // 3. Create Sample Members (School Students)
        $member1 = Member::create([
            'student_id_number' => '12001',
            'full_name' => 'Ahmad Fajar Prasetyo',
            'grade_level' => '10',
            'class_name' => 'X RPL 1',
            'major' => 'RPL',
            'cohort' => '2026',
            'gender' => 'male',
            'email' => 'ahmad.fajar@siswa.edubank.test',
            'phone_number' => '081234567891',
            'address' => 'Bantul, D.I. Yogyakarta',
            'registration_status' => 'registered',
        ]);

        $member2 = Member::create([
            'student_id_number' => '12002',
            'full_name' => 'Siti Nur Aisyah',
            'grade_level' => '10',
            'class_name' => 'X AKL 1',
            'major' => 'AKL',
            'cohort' => '2026',
            'gender' => 'female',
            'email' => 'siti.aisyah@siswa.edubank.test',
            'phone_number' => '081234567892',
            'address' => 'Bantul, D.I. Yogyakarta',
            'registration_status' => 'unregistered',
        ]);

        $member3 = Member::create([
            'student_id_number' => '12003',
            'full_name' => 'Budi Santoso',
            'grade_level' => '10',
            'class_name' => 'X TKJ 2',
            'major' => 'TKJ',
            'cohort' => '2026',
            'gender' => 'male',
            'email' => 'budi.santoso@siswa.edubank.test',
            'phone_number' => '081234567893',
            'address' => 'Sleman, D.I. Yogyakarta',
            'registration_status' => 'unregistered',
        ]);

        $member4 = Member::create([
            'student_id_number' => '12004',
            'full_name' => 'Dewi Lestari',
            'grade_level' => '10',
            'class_name' => 'X MPLB 1',
            'major' => 'MPLB',
            'cohort' => '2026',
            'gender' => 'female',
            'email' => 'dewi.lestari@siswa.edubank.test',
            'phone_number' => '081234567894',
            'address' => 'Yogyakarta',
            'registration_status' => 'unregistered',
        ]);

        $member5 = Member::create([
            'student_id_number' => '12005',
            'full_name' => 'Rizky Pratama',
            'grade_level' => '10',
            'class_name' => 'X DKV 1',
            'major' => 'DKV',
            'cohort' => '2026',
            'gender' => 'male',
            'email' => 'rizky.pratama@siswa.edubank.test',
            'phone_number' => '081234567895',
            'address' => 'Bantul, D.I. Yogyakarta',
            'registration_status' => 'unregistered',
        ]);

        // 4. Create Active Saver for Member 1 (Ahmad Fajar)
        $userSaver = User::create([
            'name' => $member1->full_name,
            'email' => $member1->email,
            'password' => Hash::make('password'),
            'role' => 'saver',
            'status' => 'active',
        ]);

        $saver1 = Saver::create([
            'member_id' => $member1->id,
            'user_id' => $userSaver->id,
            'account_number' => 'RPL-2026-0001',
            'student_id_number' => $member1->student_id_number,
            'full_name' => $member1->full_name,
            'grade_level' => $member1->grade_level,
            'class_name' => $member1->class_name,
            'major' => $member1->major,
            'cohort' => $member1->cohort,
            'security_pin' => Hash::make('123456'), // Default PIN 123456
            'balance' => 10000,
            'qr_code_path' => null,
            'joined_date' => now()->toDateString(),
            'status' => 'active',
        ]);

        // 4b. Create Account record for saver1
        $account1 = \App\Models\Account::create([
            'saver_id'        => $saver1->id,
            'account_number'  => $saver1->account_number,
            'account_type'    => 'student_savings',
            'balance'         => 10000,
            'minimum_balance' => 10000,
            'status'          => 'active',
            'opened_date'     => now()->toDateString(),
        ]);

        // 5. Initial Deposit Transaction (Rp 10.000)
        $transaction = Transaction::create([
            'transaction_code' => 'TRX-' . date('Ymd') . '-0001',
            'saver_id'         => $saver1->id,
            'account_id'       => $account1->id,
            'user_id'          => $admin->id,
            'transaction_type' => 'deposit',
            'amount'           => 10000,
            'balance_before'   => 0,
            'balance_after'    => 10000,
            'description'      => 'Initial Account Opening Deposit',
            'transaction_date' => now(),
        ]);

        // 6. Double-Entry Accounting Journal for Initial Deposit
        // Debit: Kas Teller (101)
        AccountingJournal::create([
            'transaction_id' => $transaction->id,
            'account_code'   => '101',
            'account_name'   => 'Cash at Teller',
            'position'       => 'debit',
            'amount'         => 10000,
            'entry_date'     => now(),
        ]);

        // Credit: Simpanan Nasabah (201)
        AccountingJournal::create([
            'transaction_id' => $transaction->id,
            'account_code'   => '201',
            'account_name'   => 'Student Savings Liability',
            'position'       => 'credit',
            'amount'         => 10000,
            'entry_date'     => now(),
        ]);

        // 7. Seed System Settings
        $this->call(SettingSeeder::class);

        // 8. Sample Daily Closings
        \App\Models\DailyClosing::firstOrCreate(
            ['closing_date' => now()->toDateString(), 'teller_id' => $teller->id],
            [
                'admin_id'          => null,
                'opening_balance'   => 1000000,
                'total_deposits'    => 2500000,
                'total_withdrawals' => 500000,
                'system_balance'    => 3000000,
                'physical_cash'     => 3000000,
                'difference'        => 0,
                'status'            => 'submitted',
                'vault_status'      => 'with_teller',
                'notes'             => 'Penutupan kas operasional harian teller loket 1. Fisik uang seimbang.',
            ]
        );

        \App\Models\DailyClosing::firstOrCreate(
            ['closing_date' => now()->subDay()->toDateString(), 'teller_id' => $teller->id],
            [
                'admin_id'          => null,
                'opening_balance'   => 1000000,
                'total_deposits'    => 1800000,
                'total_withdrawals' => 300000,
                'system_balance'    => 2500000,
                'physical_cash'     => 2450000,
                'difference'        => -50000,
                'status'            => 'submitted',
                'vault_status'      => 'with_teller',
                'notes'             => 'Fisik kas laci teller saat penutupan shift sore. Mohon diperiksa kembali oleh admin.',
            ]
        );
    }
}
