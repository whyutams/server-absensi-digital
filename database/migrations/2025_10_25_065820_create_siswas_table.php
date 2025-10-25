<?php

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
        Schema::create('siswas', function (Blueprint $table) {
            $table->id();
            $table->string('nis')->unique()->nullable();
            $table->string('nama')->nullable();
            $table->string('nomor_hp')->nullable();
            $table->integer('jurusan_id')->unsigned()->foreign()->references('id')->on('jurusans')->onDelete('set null')->nullable();
            $table->integer('kelas_id')->unsigned()->foreign()->references('id')->on('kelas')->onDelete('set null')->nullable();
            $table->integer('orangtua_id')->unsigned()->foreign()->references('id')->on('users')->onDelete('set null')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('siswas');
    }
};
