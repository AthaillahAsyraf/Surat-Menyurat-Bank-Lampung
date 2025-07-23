<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('surat_reads', function (Blueprint $table) {
            $table->id();
            $table->foreignId('surat_id')->constrained('surats')->onDelete('cascade');
            $table->foreignId('kantor_cabang_id')->constrained('kantor_cabangs');
            $table->timestamp('read_at');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('surat_reads');
    }
};