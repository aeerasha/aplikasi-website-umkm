<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('order_items', function (Blueprint $table) {

            $table->id();

            // relasi order
            $table->foreignId('order_id')
                  ->constrained()
                  ->cascadeOnDelete();

            // relasi product
            $table->foreignId('product_id')
                  ->constrained()
                  ->cascadeOnDelete();

            // jumlah barang
            $table->unsignedInteger('quantity');

            // harga saat transaksi
            $table->decimal('price', 12, 2);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};
