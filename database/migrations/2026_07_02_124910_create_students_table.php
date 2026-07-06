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
    Schema::create('students', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained()->onDelete('cascade');
        $table->foreignId('school_year_id')->constrained();

        $table->string('first_name');
        $table->string('last_name');
        $table->string('middle_name')->nullable();
        $table->date('birthdate');
        $table->string('address');
        $table->string('grade_level');
        $table->string('section')->nullable();

        $table->string('parent_name');
        $table->string('parent_contact');
        $table->string('parent_relationship'); // mother, father, guardian, etc.

        $table->enum('enrollment_status', ['pending', 'interview', 'approved', 'rejected'])->default('pending');

        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
