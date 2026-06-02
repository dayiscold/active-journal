<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('group_discipline', function (Blueprint $table) {
            $table->id();
            $table->foreignId('group_id')->constrained()->cascadeOnDelete();
            $table->foreignId('discipline_id')->constrained()->cascadeOnDelete();
            $table->foreignId('teacher_id')->constrained('users')->cascadeOnDelete();
            $table->string('semester');
            $table->timestamps();

            $table->unique(['group_id', 'discipline_id', 'semester'], 'group_discipline_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('group_discipline');
    }
};
