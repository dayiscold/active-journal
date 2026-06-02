<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('attendance', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lesson_id')->constrained()->cascadeOnDelete();
            $table->foreignId('student_id')->constrained('users')->cascadeOnDelete();
            $table->enum('status', ['present', 'absent', 'late', 'sick']);
            $table->text('reason')->nullable();
            $table->timestamps();

            $table->unique(['lesson_id', 'student_id'], 'attendance_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attendance');
    }
};
