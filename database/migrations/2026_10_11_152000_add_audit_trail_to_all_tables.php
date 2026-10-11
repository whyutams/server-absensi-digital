<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. keterlambatans
        if (Schema::hasTable('keterlambatans') && !Schema::hasColumn('keterlambatans', 'updated_by')) {
            Schema::table('keterlambatans', function (Blueprint $table) {
                $table->foreignId('updated_by')->nullable()->after('created_by')->constrained('users')->nullOnDelete();
            });
        }

        // 2. perizinans
        if (Schema::hasTable('perizinans') && !Schema::hasColumn('perizinans', 'updated_by')) {
            Schema::table('perizinans', function (Blueprint $table) {
                $table->foreignId('updated_by')->nullable()->after('created_by')->constrained('users')->nullOnDelete();
            });
        }

        // 3. siswas
        if (Schema::hasTable('siswas')) {
            Schema::table('siswas', function (Blueprint $table) {
                if (!Schema::hasColumn('siswas', 'created_by')) {
                    $table->foreignId('created_by')->nullable()->after('orangtua_id')->constrained('users')->nullOnDelete();
                }
                if (!Schema::hasColumn('siswas', 'updated_by')) {
                    $table->foreignId('updated_by')->nullable()->after('created_by')->constrained('users')->nullOnDelete();
                }
            });
        }

        // 4. jurusans
        if (Schema::hasTable('jurusans')) {
            Schema::table('jurusans', function (Blueprint $table) {
                if (!Schema::hasColumn('jurusans', 'created_by')) {
                    $table->foreignId('created_by')->nullable()->after('deskripsi')->constrained('users')->nullOnDelete();
                }
                if (!Schema::hasColumn('jurusans', 'updated_by')) {
                    $table->foreignId('updated_by')->nullable()->after('created_by')->constrained('users')->nullOnDelete();
                }
            });
        }

        // 5. kelas
        if (Schema::hasTable('kelas')) {
            Schema::table('kelas', function (Blueprint $table) {
                if (!Schema::hasColumn('kelas', 'created_by')) {
                    $table->foreignId('created_by')->nullable()->after('jurusan_id')->constrained('users')->nullOnDelete();
                }
                if (!Schema::hasColumn('kelas', 'updated_by')) {
                    $table->foreignId('updated_by')->nullable()->after('created_by')->constrained('users')->nullOnDelete();
                }
            });
        }

        // 6. users
        if (Schema::hasTable('users') && !Schema::hasColumn('users', 'created_by')) {
            Schema::table('users', function (Blueprint $table) {
                $table->foreignId('created_by')->nullable()->after('password')->constrained('users')->nullOnDelete();
            });
        }

        // Set existing records: created_by = null, updated_by = null, and timestamps = now()
        $now = now();

        DB::table('keterlambatans')->update([
            'created_by' => null,
            'updated_by' => null,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('perizinans')->update([
            'created_by' => null,
            'updated_by' => null,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('siswas')->update([
            'created_by' => null,
            'updated_by' => null,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('jurusans')->update([
            'created_by' => null,
            'updated_by' => null,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('kelas')->update([
            'created_by' => null,
            'updated_by' => null,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('users')->update([
            'created_by' => null,
            'created_at' => $now,
            'updated_at' => $now,
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('keterlambatans', 'updated_by')) {
            Schema::table('keterlambatans', function (Blueprint $table) {
                $table->dropConstrainedForeignId('updated_by');
            });
        }

        if (Schema::hasColumn('perizinans', 'updated_by')) {
            Schema::table('perizinans', function (Blueprint $table) {
                $table->dropConstrainedForeignId('updated_by');
            });
        }

        if (Schema::hasTable('siswas')) {
            Schema::table('siswas', function (Blueprint $table) {
                if (Schema::hasColumn('siswas', 'updated_by')) {
                    $table->dropConstrainedForeignId('updated_by');
                }
                if (Schema::hasColumn('siswas', 'created_by')) {
                    $table->dropConstrainedForeignId('created_by');
                }
            });
        }

        if (Schema::hasTable('jurusans')) {
            Schema::table('jurusans', function (Blueprint $table) {
                if (Schema::hasColumn('jurusans', 'updated_by')) {
                    $table->dropConstrainedForeignId('updated_by');
                }
                if (Schema::hasColumn('jurusans', 'created_by')) {
                    $table->dropConstrainedForeignId('created_by');
                }
            });
        }

        if (Schema::hasTable('kelas')) {
            Schema::table('kelas', function (Blueprint $table) {
                if (Schema::hasColumn('kelas', 'updated_by')) {
                    $table->dropConstrainedForeignId('updated_by');
                }
                if (Schema::hasColumn('kelas', 'created_by')) {
                    $table->dropConstrainedForeignId('created_by');
                }
            });
        }

        if (Schema::hasColumn('users', 'created_by')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropConstrainedForeignId('created_by');
            });
        }
    }
};
