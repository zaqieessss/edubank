<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            [
                'key'         => 'school_name',
                'value'       => 'SMK Negeri 1 Bantul',
                'label'       => 'Nama Sekolah',
                'type'        => 'text',
                'group'       => 'school',
                'description' => 'Nama resmi instansi satuan pendidikan.',
            ],
            [
                'key'         => 'bank_name',
                'value'       => 'Bank Mini Edubank',
                'label'       => 'Nama Bank Mini',
                'type'        => 'text',
                'group'       => 'banking',
                'description' => 'Nama unit usaha bank mini sekolah.',
            ],
            [
                'key'         => 'min_holding_balance',
                'value'       => '10000',
                'label'       => 'Batas Saldo Mengendap Minimal (Rp)',
                'type'        => 'number',
                'group'       => 'banking',
                'description' => 'Saldo minimum yang wajib tersisa di rekening nasabah setelah penarikan tunai.',
            ],
            [
                'key'         => 'min_transaction_amount',
                'value'       => '10000',
                'label'       => 'Minimal Nominal Transaksi (Rp)',
                'type'        => 'number',
                'group'       => 'banking',
                'description' => 'Batas minimum transaksi setoran dan penarikan di loket teller.',
            ],
            [
                'key'         => 'academic_year',
                'value'       => '2026/2027',
                'label'       => 'Tahun Ajaran Aktif',
                'type'        => 'text',
                'group'       => 'school',
                'description' => 'Tahun ajaran berjalan untuk penomoran rekening dan pelaporan.',
            ],
            [
                'key'         => 'active_cohort',
                'value'       => '2026',
                'label'       => 'Angkatan Siswa Baru Aktif',
                'type'        => 'text',
                'group'       => 'school',
                'description' => 'Tahun angkatan untuk registrasi murid baru.',
            ],
            [
                'key'         => 'auto_journal_enabled',
                'value'       => '1',
                'label'       => 'Otomatisasi Jurnal Double-Entry',
                'type'        => 'boolean',
                'group'       => 'banking',
                'description' => 'Otomatisasi pencatatan buku besar 101 Kas dan 201 Simpanan saat ada transaksi.',
            ],
        ];

        foreach ($settings as $item) {
            Setting::updateOrCreate(['key' => $item['key']], $item);
        }
    }
}
