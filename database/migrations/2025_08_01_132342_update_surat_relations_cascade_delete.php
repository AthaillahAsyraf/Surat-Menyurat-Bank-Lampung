<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateSuratRelationsCascadeDelete extends Migration
{
    public function up()
    {
        // Update foreign key surat_penerimas
        Schema::table('surat_penerimas', function (Blueprint $table) {
            $table->dropForeign(['surat_id']);
            $table->foreign('surat_id')
                  ->references('id')
                  ->on('surats')
                  ->onDelete('cascade');
        });
        
        // Update foreign key surat_reads
        Schema::table('surat_reads', function (Blueprint $table) {
            $table->dropForeign(['surat_id']);
            $table->foreign('surat_id')
                  ->references('id')
                  ->on('surats')
                  ->onDelete('cascade');
        });
        
        // Update foreign key untuk parent_id (balasan)
        Schema::table('surats', function (Blueprint $table) {
            $table->dropForeign(['parent_id']);
            $table->foreign('parent_id')
                  ->references('id')
                  ->on('surats')
                  ->onDelete('cascade');
        });
    }
    
    public function down()
    {
        // Kembalikan ke restrict
        Schema::table('surat_penerimas', function (Blueprint $table) {
            $table->dropForeign(['surat_id']);
            $table->foreign('surat_id')
                  ->references('id')
                  ->on('surats')
                  ->onDelete('restrict');
        });
        
        Schema::table('surat_reads', function (Blueprint $table) {
            $table->dropForeign(['surat_id']);
            $table->foreign('surat_id')
                  ->references('id')
                  ->on('surats')
                  ->onDelete('restrict');
        });
        
        Schema::table('surats', function (Blueprint $table) {
            $table->dropForeign(['parent_id']);
            $table->foreign('parent_id')
                  ->references('id')
                  ->on('surats')
                  ->onDelete('restrict');
        });
    }
}