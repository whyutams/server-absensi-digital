<?php

use App\Models\Perizinan;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('perizinans', function (Blueprint $table) {
            $table->id();
            $table->text('keterangan')->nullable();
            $table->string('gambar')->nullable();
            $table->enum('status', [Perizinan::STATUS_APPROVED, Perizinan::STATUS_PENDING, Perizinan::STATUS_REJECTED])->default(Perizinan::STATUS_PENDING);
            $table->integer('siswa_id')->unsigned()->foreign()->references('id')->on('siswas')->onDelete('cascade');
            $table->integer('created_by')->unsigned()->foreign()->references('id')->on('users')->onDelete('cascade')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('perizinans');
    }
};
