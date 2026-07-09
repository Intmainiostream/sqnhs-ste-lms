<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('student_grades', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('students')->cascadeOnDelete();
            $table->foreignId('subject_id')->constrained('subjects')->cascadeOnDelete();
            $table->foreignId('school_year_id')->constrained('school_years')->cascadeOnDelete();
            $table->decimal('term1', 5, 2)->nullable();
            $table->decimal('term2', 5, 2)->nullable();
            $table->decimal('term3', 5, 2)->nullable();
            $table->decimal('final_grade', 5, 2)->nullable();
            $table->boolean('is_override')->default(false);
            $table->string('remarks')->nullable();
            $table->timestamps();

            $table->unique(['student_id', 'subject_id', 'school_year_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('student_grades');
    }
};