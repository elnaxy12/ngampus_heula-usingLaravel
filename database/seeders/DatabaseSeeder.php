<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Mahasiswa; 
class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        Mahasiswa::create([
            'nim' => '230001',
            'nama' => 'Sopian Aji',
            'alamat' => 'Jl. Melati No. 1, Jakarta',
            'tanggal_lahir' => '2000-04-12',
        ]);

        Mahasiswa::create([
            'nim' => '230002',
            'nama' => 'Husni Faqih',
            'alamat' => 'Jl. Mawar No. 5, Bandung',
            'tanggal_lahir' => '1999-11-23',
        ]);

        Mahasiswa::create([
            'nim' => '230003',
            'nama' => 'Rousyati',
            'alamat' => 'Jl. Kenanga No. 9, Surabaya',
            'tanggal_lahir' => '2001-02-17',
        ]);
    }
}
