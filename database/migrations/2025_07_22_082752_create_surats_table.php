<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('surats', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_surat')->unique();
            $table->string('perihal');
            $table->text('isi_surat');
            $table->enum('jenis_surat', ['informasi', 'pertanyaan', 'permintaan', 'lainnya']);
            $table->foreignId('pengirim_id')->constrained('kantor_cabangs');
            $table->string('file_lampiran')->nullable();
            $table->enum('status', ['terkirim', 'dibaca'])->default('terkirim');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('surats');
    }
};