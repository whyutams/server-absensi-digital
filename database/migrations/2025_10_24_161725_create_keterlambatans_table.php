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
        Schema::create('keterlambatans', function (Blueprint $table) {
            $table->id();
            $table->string('nis')->nullable();
            $table->string('nomor_hp')->nullable();
            $table->string('nama')->nullable();
            $table->text('keterangan')->nullable();
            $table->string('gambar')->nullable();
            $table->integer('created_by')->unsigned()->foreign()->references('id')->on('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('keterlambatans');
    }
};
