<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDetailPeriksaTable extends Migration {
    public function up() {
        Schema::create('detail_periksa', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_periksa')->constrained('periksa')->onDelete('cascade');
            $table->foreignId('id_obat')->constrained('obat')->onDelete('cascade');
            $table->integer('jumlah');
            $table->timestamps();
        });
    }

    public function down() {
        Schema::dropIfExists('detail_periksa');
    }
}
