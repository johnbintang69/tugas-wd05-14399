<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Add keluhan, rename catatan to catatan_dokter and add status
        Schema::table('periksa', function (Blueprint $table) {
            $table->text('keluhan')->after('tgl_periksa');
            // rename catatan to catatan_dokter
            $table->renameColumn('catatan', 'catatan_dokter');
            $table->enum('status', ['pending', 'done', 'cancelled'])->default('pending')->after('catatan_dokter');
        });
    }

    public function down(): void
    {
        Schema::table('periksa', function (Blueprint $table) {
            $table->dropColumn('status');
            $table->text('catatan')->change();
            $table->renameColumn('catatan_dokter', 'catatan');
            $table->dropColumn('keluhan');
        });
    }
};