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
            ['nama' => 'Kantor Pusat', 'kode' => '370', 'alamat' => 'Jl. Wolter Monginsidi No.182, Kota Bandar Lampung'],
            ['nama' => 'KC Bandar Lampung', 'kode' => '380', 'alamat' => 'Jl. Wolter Monginsidi Nomor.75, Kota Bandar Lampung'],
            ['nama' => 'KC Metro', 'kode' => '381', 'alamat' => 'Jl. A.Yani No. 5, Kota Metro, Lampung'],
            ['nama' => 'KC Kotabumi', 'kode' => '382', 'alamat' => 'Jl. Jend. Sudirman No.134, Kota Gapura, Kotabumi, Kabupaten Lampung Utara'],
            ['nama' => 'KC Kalianda', 'kode' => '383', 'alamat' => 'Kalianda, Kec. Kalianda, Kabupaten Lampung Selatan'],
            ['nama' => 'KC Pringsewu', 'kode' => '384', 'alamat' => 'Jl. Jend. Sudirman No. 36A RT.06 RW.01 LK. I Kelurahan Pringsewu Selatan'],
            ['nama' => 'KC Bandar Jaya', 'kode' => '385', 'alamat' => 'Jl. Proklamator No.134, Bandar Jaya Bar., Bandarjaya, Kabupaten Lampung Tengah'],
            ['nama' => 'KCP Bukit Kemuning', 'kode' => '386', 'alamat' => 'Jl. Sumberjaya No.4, Bukit Kemuning Kab. Lampung Utara'],
            ['nama' => 'KCP Liwa', 'kode' => '387', 'alamat' => 'Jl. Raden Intan Nomor 5 Kec. Way Mengaku, Kab. Lampung Barat'],
            ['nama' => 'KCP Menggala', 'kode' => '388', 'alamat' => 'Jl. Raya Gunung Sakti No.29 Menggala Kab.Tulangbawang'],
            ['nama' => 'KCP Kota Agung', 'kode' => '389', 'alamat' => 'Jl. Merdeka No. 8-10 Pasar Madang Kota Agung Kab. Tanggamus'],
            ['nama' => 'KCP Panjang', 'kode' => '390', 'alamat' => 'Jl. Yos Sudarso Panjang No 115 Panjang Utara Kota Bandar Lampung'],
            ['nama' => 'KCP Kartini', 'kode' => '391', 'alamat' => 'Jl. RA Kartini Blok C- D No.99, Tj. Karang, Engal, Kota Bandar Lampung'],
            ['nama' => 'KCP Ryacudu', 'kode' => '392', 'alamat' => 'Jl. Ryacudu Kelurahan Korpri Raya Kecamatan Sukarame'],
            ['nama' => 'KCP Krui', 'kode' => '393', 'alamat' => 'Jl. Mulia No. 03 Lingkungan Pasar Mulya Selatan I Kel. Pasar Krui Kec. Pesisir Tengah Kab. Pesisir Barat'],
            ['nama' => 'KCP Unit 2', 'kode' => '394', 'alamat' => 'Jl. Raya Lintas Timur Banjar Agung Kab.Tulang Bawang, Lampung'],
            ['nama' => 'KCP Talang Padang', 'kode' => '395', 'alamat' => 'Jl. Kota Agung-Balimbing (Pasar Talang Padang) Kec. Talang Padang'],
            ['nama' => 'KCP Panaragan Jaya', 'kode' => '396', 'alamat' => 'JI. Raya Panaragan Jaya, Kec. Tulang bawang Tengah'],
            ['nama' => 'KCP Antasari', 'kode' => '397', 'alamat' => 'Jl. P. Antasari No.35 A, Kedamaian, Kota Bandar Lampung'],
            ['nama' => 'KCP Baradatu', 'kode' => '398', 'alamat' => 'Jl. Negara Baradatu No.180 A Baradatu Kab. Way Kanan'],
            ['nama' => 'KCP Sukadana', 'kode' => '399', 'alamat' => 'Jl. Sukarno Hatta No. 50 Sukadana Kab. Lampung Timur'],
            ['nama' => 'KCP Gading Rejo', 'kode' => '400', 'alamat' => 'Jl. Pasar Gading Rejo Blok A Kabupaten Pringsewu'],
            ['nama' => 'KC Jakarta', 'kode' => '401', 'alamat' => 'Jl. Fatmawati Raya No.50, Cilandak Barat, Kecamatan Cilandak, Kota Jakarta Selatan'],
            ['nama' => 'KCP Sidomulyo', 'kode' => '402', 'alamat' => 'Jl. Raya Sidomulyo No. 640 Sidomulyo Kab. Lampung Selatan'],
            ['nama' => 'KCP Natar', 'kode' => '403', 'alamat' => 'Jl. Raya Natar Kab. Lampung Selatan, Lampung'],
            ['nama' => 'KCP Bakauheni', 'kode' => '404', 'alamat' => 'Jl. Raya Lintas Bakauheni Kab. Lampung Selatan'],
            ['nama' => 'KCP Teuku Umar', 'kode' => '405', 'alamat' => 'Jl. Teuku Umar No.17C dan D Kec Kedaton Kota Bandar Lampung'],
            ['nama' => 'KCP Way Jepara', 'kode' => '406', 'alamat' => 'JL. Raya Lintas Timur no. 278 Kel. Labuhan Ratu I, Kec. Way Jepara'],
            ['nama' => 'KCP Gedong Tataan', 'kode' => '407', 'alamat' => 'Jl. Ahmad Yani Desa Sukaraja Kec. Gedong Tataan Kabupaten Pesawaran, Lampung'],
            ['nama' => 'KCP Tanjung Bintang', 'kode' => '408', 'alamat' => 'Jalan Raya Pasar Tanjung Bintang No. 3 CD RT 001 RW 003 Kelurahan Jati Baru Tanjung Bintang'],
            ['nama' => 'KCP Simpang Pematang', 'kode' => '409', 'alamat' => 'Jl. Raya Simpang Pematang RT.024 RW.007 Kabupaten Mesuji'],
            ['nama' => 'KCP Hanura', 'kode' => '410', 'alamat' => 'Jl. Teluk Ratai Dusun B No. 343, RT. 003 RW. 002, Desa Hanura, Kec. Teluk Pandan Kab. Pesawaran'],
            ['nama' => 'KCP Kota Gajah', 'kode' => '411', 'alamat' => 'Komp. Perkantoran & Pertokoan Pareca Kencana Jl. Raya Gunung Sugih No. 14 & 15 Kota Gajah Kab. Lampung Tengah'],
            ['nama' => 'KCP Abung Semuli', 'kode' => '412', 'alamat' => 'Semuli Raya RT.004/RW 003 Desa Semuli Raya Kec Abung Semuli Kab. Lampung Utara'],
            ['nama' => 'KCP Teluk Betung Selatan', 'kode' => '413', 'alamat' => 'Jl. Laksamana Malahayati No.188 Kec. Bumi Waras, Kota Bandar Lampung, Lampung'],
            ['nama' => 'KCP Mulya Asri', 'kode' => '414', 'alamat' => 'Jl. Merdeka No.70 Kel. Mulya Asri, Kec. Tulang bawang Tengah, Kab. Tulang Bawang Barat'],
            ['nama' => 'KCP Rawajitu Selatan', 'kode' => '415', 'alamat' => 'Desa Gedung Karya Jitu Kec. Rawajitu Selatan, Kab. Tulang Bawang'],
            ['nama' => 'KCP Pekalongan', 'kode' => '416', 'alamat' => 'Jalan Raya Pekalongan Dusun IV RT 22/RW 08 Kelurahan Pekalongan Kecamatan Pekalongan Kab. Lampung Timur 34391'],
            ['nama' => 'KCP Kalirejo', 'kode' => '417', 'alamat' => 'Jalan Jenderal Sudirman Kecamatan Kalirejo Kabupaten Lampung Tengah'],
            ['nama' => 'KCP Pulung Kencana', 'kode' => '', 'alamat' => 'Pulung Kencana, Tulang Bawang Tengah, Kab. Tulang Bawang Barat, Lampung'],
            ['nama' => 'KCP Daya Murni', 'kode' => '', 'alamat' => 'JL. Jend. Sudirman Daya Asri No.216 Kec. Tumijajar Kab. Tulang Bawang Barat'],
            ['nama' => 'KCP kemiling', 'kode' => '418', 'alamat' => 'Jalan Teuku Cik Ditiro No. 35-36 Kec. Kemiling, Bandar Lampung']
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