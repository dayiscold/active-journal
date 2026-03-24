<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'role')) {
                $table->string('role')->default('student')->after('password');
            }
            if (!Schema::hasColumn('users', 'group_id')) {
                $table->foreignId('group_id')->nullable()->after('role')->constrained()->nullOnDelete();
            }
            if (!Schema::hasColumn('users', 'teams_email')) {
                $table->string('teams_email')->nullable()->after('group_id');
            }
            if (!Schema::hasColumn('users', 'student_id')) {
                $table->string('student_id')->nullable()->unique()->after('teams_email');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['role', 'group_id', 'teams_email', 'student_id']);
        });
    }
};
