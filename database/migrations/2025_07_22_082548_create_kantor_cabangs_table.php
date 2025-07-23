<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('kantor_cabangs', function (Blueprint $table) {
            $table->id();
            $table->string('nama_kantor');
            $table->string('kode_kantor')->unique();
            $table->string('alamat');
            $table->string('no_telp')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('kantor_cabangs');
    }
};