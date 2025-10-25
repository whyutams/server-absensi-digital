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
            $table->text('keterangan')->nullable();
            $table->string('gambar')->nullable();
            $table->integer('siswa_id')->unsigned()->foreign()->references('id')->on('siswas')->onDelete('cascade');
            $table->integer('created_by')->unsigned()->foreign()->references('id')->on('users')->onDelete('set null')->nullable();
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
