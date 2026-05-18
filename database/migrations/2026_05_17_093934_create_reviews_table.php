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
        Schema::create('reviews', function (Blueprint $table) {

            $table->id();

            // Bisa kosong untuk anonim
            $table->string('customer_name')->nullable();

            // Nomor meja
            $table->string('table_number');

            // Waktu kunjungan
            $table->time('visit_time')->nullable();

            // Rating 1-5
            $table->integer('rating');

            // Komentar pelanggan
            $table->text('comment')->nullable();

            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};