<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->string('first_name')->nullable()->change();
            $table->string('last_name')->nullable()->change();
            $table->string('middle_name')->nullable()->change();
            $table->date('birthdate')->nullable()->change();
            $table->string('address')->nullable()->change();
            $table->string('parent_name')->nullable()->change();
            $table->string('parent_contact')->nullable()->change();
            $table->string('parent_relationship')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->string('first_name')->nullable(false)->change();
            $table->string('last_name')->nullable(false)->change();
            $table->string('middle_name')->nullable(false)->change();
            $table->date('birthdate')->nullable(false)->change();
            $table->string('address')->nullable(false)->change();
            $table->string('parent_name')->nullable(false)->change();
            $table->string('parent_contact')->nullable(false)->change();
            $table->string('parent_relationship')->nullable(false)->change();
        });
    }
};