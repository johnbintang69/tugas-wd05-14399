<?php
// database/migrations/2025_06_14_173003_reset_database_structure.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Drop semua table lama jika ada
        Schema::dropIfExists('detail_periksa');
        Schema::dropIfExists('periksa');
        Schema::dropIfExists('daftar_poli');
        Schema::dropIfExists('jadwal_periksa');
        Schema::dropIfExists('pasien');
        Schema::dropIfExists('dokter');
        Schema::dropIfExists('poli');
        Schema::dropIfExists('obat');

        // 1. Table obat
        Schema::create('obat', function (Blueprint $table) {
            $table->id();
            $table->string('nama_obat', 50);
            $table->string('kemasan', 35)->nullable();
            $table->unsignedInteger('harga')->default(0);
            $table->timestamps();
        });

        // 2. Table poli (poliklinik)
        Schema::create('poli', function (Blueprint $table) {
            $table->id();
            $table->string('nama_poli', 25);
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });

        // 3. Table dokter
        Schema::create('dokter', function (Blueprint $table) {
            $table->id();
            $table->string('nama', 150);
            $table->string('alamat', 255)->nullable();
            $table->string('no_hp', 15); // Ubah ke string untuk format yang lebih fleksibel
            $table->foreignId('id_poli')->constrained('poli')->onDelete('cascade');
            $table->timestamps();
        });

        // 4. Table pasien
        Schema::create('pasien', function (Blueprint $table) {
            $table->id();
            $table->string('nama', 150);
            $table->string('alamat', 255);
            $table->string('no_ktp', 16)->unique(); // NIK 16 digit
            $table->string('no_hp', 15);
            $table->string('no_rm', 10)->unique()->nullable(); // Auto generate
            $table->timestamps();
        });

        // 5. Table jadwal_periksa
        Schema::create('jadwal_periksa', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_dokter')->constrained('dokter')->onDelete('cascade');
            $table->enum('hari', ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu']);
            $table->time('jam_mulai');
            $table->time('jam_selesai');
            $table->boolean('aktif')->default(true); // Tambahan: bisa nonaktifkan jadwal
            $table->timestamps();
            
            // Constraint: satu dokter tidak boleh punya jadwal yang overlap di hari yang sama
            $table->unique(['id_dokter', 'hari', 'jam_mulai']);
        });

        // 6. Table daftar_poli
        Schema::create('daftar_poli', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_pasien')->constrained('pasien')->onDelete('cascade');
            $table->foreignId('id_jadwal')->constrained('jadwal_periksa')->onDelete('cascade');
            $table->text('keluhan');
            $table->unsignedInteger('no_antrian')->nullable();
            $table->enum('status', ['menunggu', 'sedang_diperiksa', 'selesai', 'batal'])->default('menunggu');
            $table->date('tanggal_daftar'); // Tambahan: tanggal kapan pasien daftar
            $table->timestamps();
        });

        // 7. Table periksa
        Schema::create('periksa', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_daftar_poli')->constrained('daftar_poli')->onDelete('cascade');
            $table->date('tgl_periksa');
            $table->text('catatan');
            $table->unsignedInteger('biaya_periksa')->nullable();
            $table->timestamps();
        });

        // 8. Table detail_periksa
        Schema::create('detail_periksa', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_periksa')->constrained('periksa')->onDelete('cascade');
            $table->foreignId('id_obat')->constrained('obat')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_periksa');
        Schema::dropIfExists('periksa');
        Schema::dropIfExists('daftar_poli');
        Schema::dropIfExists('jadwal_periksa');
        Schema::dropIfExists('pasien');
        Schema::dropIfExists('dokter');
        Schema::dropIfExists('poli');
        Schema::dropIfExists('obat');
    }
};