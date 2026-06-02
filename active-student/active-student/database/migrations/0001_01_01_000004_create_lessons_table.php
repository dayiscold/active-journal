<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('lessons', function (Blueprint $table) {
            $table->id();
            $table->foreignId('group_id')->constrained()->cascadeOnDelete();
            $table->foreignId('discipline_id')->constrained()->cascadeOnDelete();
            $table->foreignId('teacher_id')->constrained('users')->cascadeOnDelete();
            $table->date('date');
            $table->integer('pair_number');
            $table->timestamps();

            $table->unique(['group_id', 'discipline_id', 'date', 'pair_number'], 'lesson_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lessons');
    }
};
