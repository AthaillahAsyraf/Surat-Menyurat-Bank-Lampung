<?php
// database/seeders/DatabaseSeeder.php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\KantorCabang;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // Data 43 kantor cabang Bank Lampung
        $kantorCabangs = [
            ['nama' => 'Kantor Pusat', 'kode' => '370', 'alamat' => 'Jl. Wolter Monginsidi No.182'],
            ['nama' => 'KC Bandar Lampung', 'kode' => '380', 'alamat' => 'Jl. Raden Intan No.45'],
            ['nama' => 'KC Metro', 'kode' => '381', 'alamat' => 'Jl. Ikan Kakap No.23'],
            ['nama' => 'KC Kotabumi', 'kode' => '382', 'alamat' => 'Jl. Jend. AH Nasution No.12'],
            ['nama' => 'KC Kalianda', 'kode' => '383', 'alamat' => 'Jl. Ahmad Yani No.89'],
            ['nama' => 'KC Pringsewu', 'kode' => '384', 'alamat' => 'Jl. Trans Sumatera No.56'],
            ['nama' => 'KC Bandar Jaya', 'kode' => '385', 'alamat' => 'Jl. Jend. Sudirman No.34'],
            ['nama' => 'KCP Bukit', 'kode' => '386', 'alamat' => 'Jl. Raya Liwa No.78'],
            ['nama' => 'KCP Liwa', 'kode' => '387', 'alamat' => 'Jl. Lintas Timur No.90'],
            ['nama' => 'KCP Menggala', 'kode' => '388', 'alamat' => 'Jl. Raya Kota Agung No.101'],
            ['nama' => 'KCP Kota Agung', 'kode' => '389', 'alamat' => 'Jl. Zainal Abidin PA No.45'],
            ['nama' => 'KCP Panjang', 'kode' => '390', 'alamat' => 'Jl. Imam Bonjol No.67'],
            ['nama' => 'KCP Kartini', 'kode' => '391', 'alamat' => 'Jl. Pulau Damar No.23'],
            ['nama' => 'KCP Ryacudu', 'kode' => '392', 'alamat' => 'Jl. Teuku Umar No.89'],
            ['nama' => 'KCP Krui', 'kode' => '393', 'alamat' => 'Jl. Letjen Alamsyah RP No.12'],
            ['nama' => 'KCP Unit 2', 'kode' => '394', 'alamat' => 'Jl. Raya Natar No.56'],
            ['nama' => 'KCP Talang Padang', 'kode' => '395', 'alamat' => 'Jl. Lintas Tengah No.78'],
            ['nama' => 'KCP Panaragan Jaya', 'kode' => '396', 'alamat' => 'Jl. Raya Gunung Sugih No.34'],
            ['nama' => 'KCP Antasari', 'kode' => '397', 'alamat' => 'Jl. Ahmad Yani No.90'],
            ['nama' => 'KCP Baradatu', 'kode' => '398', 'alamat' => 'Jl. Lintas Barat No.45'],
            ['nama' => 'KCP Sukadana', 'kode' => '399', 'alamat' => 'Jl. Raya Pugung No.67'],
            ['nama' => 'KCP Gading Rejo', 'kode' => '400', 'alamat' => 'Jl. Trans Sumatera No.23'],
            ['nama' => 'KC Jakarta', 'kode' => '401', 'alamat' => 'Jl. Lintas Timur No.89'],
            ['nama' => 'KCP Sidomulyo', 'kode' => '402', 'alamat' => 'Jl. Raya Unit II No.12'],
            ['nama' => 'KCP Natar', 'kode' => '403', 'alamat' => 'Jl. Lintas Pantai Timur No.56'],
            ['nama' => 'KCP Bakauheni', 'kode' => '404', 'alamat' => 'Jl. Raya Mesuji No.78'],
            ['nama' => 'KCP Teuku Umar', 'kode' => '405', 'alamat' => 'Jl. Lintas Tengah No.34'],
            ['nama' => 'KCP Way Jepara', 'kode' => '406', 'alamat' => 'Jl. Raya Sukadana No.90'],
            ['nama' => 'KCP Gedong Tataan', 'kode' => '407', 'alamat' => 'Jl. Trans Sumatera No.101'],
            ['nama' => 'KCP Tanjung Bintang', 'kode' => '408', 'alamat' => 'Jl. Hasanudin No.45'],
            ['nama' => 'KCP Simpang Pematang', 'kode' => '409', 'alamat' => 'Jl. Dr. Warsito No.67'],
            ['nama' => 'KCP Hanura', 'kode' => '410', 'alamat' => 'Jl. Yos Sudarso No.23'],
            ['nama' => 'KCP Kota Gajah', 'kode' => '411', 'alamat' => 'Jl. Lintas Sumatera No.89'],
            ['nama' => 'KCP Abung Semuli', 'kode' => '412', 'alamat' => 'Jl. Raya Sekampung No.12'],
            ['nama' => 'KCP Teluk Betung Selatan', 'kode' => '413', 'alamat' => 'Jl. Lintas Pantai No.56'],
            ['nama' => 'KCP Mulya Asri', 'kode' => '414', 'alamat' => 'Jl. Raya Jabung No.78'],
            ['nama' => 'KCP Rawajitu Selatan', 'kode' => '415', 'alamat' => 'Jl. Trans Sumatera No.34'],
            ['nama' => 'KCP Pekalongan', 'kode' => '416', 'alamat' => 'Jl. Raya Mataram No.90'],
            ['nama' => 'KCP Kalirejo', 'kode' => '417', 'alamat' => 'Jl. Proklamator No.45'],
            ['nama' => 'KCP kemiling', 'kode' => '418', 'alamat' => 'Jl. Lintas Tengah No.67']
        ];

        foreach ($kantorCabangs as $kantor) {
            $kc = KantorCabang::create([
                'nama_kantor' => $kantor['nama'],
                'kode_kantor' => $kantor['kode'],
                'alamat' => $kantor['alamat'],
                'no_telp' => '0721-' . rand(100000, 999999)
            ]);

            // Buat user untuk setiap kantor cabang
            User::create([
                'name' => 'Admin ' . $kantor['nama'],
                'email' => strtolower(str_replace(' ', '', $kantor['kode'])) . '@banklampung.co.id',
                'password' => Hash::make('password123'),
                'kantor_cabang_id' => $kc->id
            ]);
        }
    }
}