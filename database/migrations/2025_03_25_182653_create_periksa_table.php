<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePeriksaTable extends Migration {
    public function up() {
        Schema::create('periksa', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_pasien')->constrained('users')->onDelete('cascade');
            $table->foreignId('id_dokter')->constrained('users')->onDelete('cascade');
            $table->dateTime('tgl_periksa');
            $table->text('catatan')->nullable();
            $table->integer('biaya_periksa');
            $table->timestamps();
        });
    }

    public function down() {
        Schema::dropIfExists('periksa');
    }
}
