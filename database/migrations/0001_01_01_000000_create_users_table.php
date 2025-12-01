<?php

use App\Models\User;
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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('nip')->nullable();
            $table->string('nama');
            $table->string('nomor_hp')->unique()->nullable();
            $table->string('email')->unique()->nullable();
            $table->string('alamat')->nullable();
            $table->string('foto_profil')->nullable();
            $table->enum('role', [User::ROLE_SUPERADMIN, User::ROLE_GURU_PIKET, User::ROLE_GURU, User::ROLE_KESISWAAN, User::ROLE_KEPSEK, User::ROLE_ORANGTUA, User::ROLE_SATPAM, User::ROLE_BK]);
            $table->integer('jurusan_id')->unsigned()->foreign()->references('id')->on('jurusans')->onDelete('set null')->nullable();
            $table->string('username')->unique();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
