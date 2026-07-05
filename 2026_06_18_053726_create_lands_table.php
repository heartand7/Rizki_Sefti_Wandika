<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lands', function (Blueprint $table) {
            $table->id();
            $table->string('nama_pemilik');
            $table->string('nama_lahan');
            $table->text('lokasi_alamat')->nullable();
            $table->text('deskripsi')->nullable();
            $table->decimal('luas_meter', 15, 2);
            $table->decimal('keliling_meter', 15, 2);
            $table->json('koordinat_polygon'); 
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lands');
    }
};