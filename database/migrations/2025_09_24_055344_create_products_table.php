<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id(); // primary key (auto increment)
            $table->string('name'); // kolom nama produk
            $table->text('description')->nullable(); // deskripsi (boleh kosong)
            $table->decimal('price', 10, 2); // harga
            $table->integer('stock'); // jumlah stok
            $table->timestamps(); // created_at & updated_at
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
