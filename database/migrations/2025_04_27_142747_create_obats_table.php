<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateObatsTable extends Migration
{
    public function up()
    {
        Schema::create('obats', function (Blueprint $table) {
            $table->id();
            $table->string('nama_obat');
            $table->integer('harga');
            $table->integer('stok');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('obats');
    }
}
