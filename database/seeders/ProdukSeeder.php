<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Produk;

class ProdukSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Produk::create(['user_id' => 1, 'kategori_id' => 1, 'nama_produk' => 'Brownies Coklat Classic', 'detail' => 'Brownies lembut fudgy dengan coklat premium', 'harga' => 45000, 'stok' => 30, 'berat' => 400, 'foto' => '20260413154923_69dcae13e90bf.jpg', 'status' => 1]);

        Produk::create(['user_id' => 1, 'kategori_id' => 1, 'nama_produk' => 'Brownies Keju Susu', 'detail' => 'Brownies coklat dengan topping keju susu melted', 'harga' => 52000, 'stok' => 25, 'berat' => 420, 'foto' => '20260413155143_69dcae9f6f0d5.jpg', 'status' => 1]);

        Produk::create(['user_id' => 1, 'kategori_id' => 1, 'nama_produk' => 'Brownies Oreo Crumble', 'detail' => 'Brownies coklat dengan taburan oreo renyah di atas', 'harga' => 55000, 'stok' => 20, 'berat' => 450, 'foto' => '20260413155534_69dcaf868ae39.png', 'status' => 1]);

        Produk::create(['user_id' => 1, 'kategori_id' => 1, 'nama_produk' => 'Brownies Kacang Almond', 'detail' => 'Brownies rich coklat dengan potongan almond panggang', 'harga' => 58000, 'stok' => 15, 'berat' => 430, 'foto' => '20260413155715_69dcafeb038a3.png', 'status' => 1]);

        Produk::create(['user_id' => 1, 'kategori_id' => 1, 'nama_produk' => 'Brownies Red Velvet', 'detail' => 'Brownies red velvet lembut dengan cream cheese frosting', 'harga' => 60000, 'stok' => 18, 'berat' => 440, 'foto' => '20260413155835_69dcb03b70cdc.png', 'status' => 1]);

        Produk::create(['user_id' => 1, 'kategori_id' => 1, 'nama_produk' => 'Brownies Matcha', 'detail' => 'Brownies matcha premium dengan swirl coklat putih', 'harga' => 62000, 'stok' => 20, 'berat' => 420, 'foto' => '20260413155943_69dcb07f6d127.png', 'status' => 1]);
    }
}
