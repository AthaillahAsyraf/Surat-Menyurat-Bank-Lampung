<?php
// database/migrations/2024_01_01_000007_create_surat_penerimas_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('surat_penerimas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('surat_id')->constrained('surats')->onDelete('cascade');
            $table->foreignId('kantor_cabang_id')->constrained('kantor_cabangs');
            $table->timestamps();
            
            $table->unique(['surat_id', 'kantor_cabang_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('surat_penerimas');
    }
};