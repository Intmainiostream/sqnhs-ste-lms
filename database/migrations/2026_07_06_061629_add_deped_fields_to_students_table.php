<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->string('lrn')->nullable()->after('user_id');
            $table->string('psa_birth_cert_no')->nullable();
            $table->string('sex')->nullable();
            $table->string('place_of_birth')->nullable();
            $table->string('mother_tongue')->nullable();
            $table->boolean('is_ip')->default(false);
            $table->string('ip_specify')->nullable();
            $table->boolean('is_4ps')->default(false);
            $table->string('household_id')->nullable();
            $table->boolean('has_disability')->default(false);
            $table->string('disability_type')->nullable();

            // Current address
            $table->string('current_house_no')->nullable();
            $table->string('current_street')->nullable();
            $table->string('current_barangay')->nullable();
            $table->string('current_city')->nullable();
            $table->string('current_province')->nullable();
            $table->string('current_zip')->nullable();

            // Permanent address
            $table->boolean('same_as_current')->default(true);
            $table->string('permanent_house_no')->nullable();
            $table->string('permanent_street')->nullable();
            $table->string('permanent_barangay')->nullable();
            $table->string('permanent_city')->nullable();
            $table->string('permanent_province')->nullable();
            $table->string('permanent_zip')->nullable();

            // Parent/guardian (split into 3 like the real form)
            $table->string('father_name')->nullable();
            $table->string('father_contact')->nullable();
            $table->string('mother_name')->nullable();
            $table->string('mother_contact')->nullable();
            $table->string('guardian_name')->nullable();
            $table->string('guardian_contact')->nullable();

            // Returning / transfer
            $table->boolean('is_returning')->default(false);
            $table->string('last_grade_completed')->nullable();
            $table->string('last_sy_completed')->nullable();
            $table->string('last_school_attended')->nullable();
            $table->string('last_school_id')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->dropColumn([
                'lrn', 'psa_birth_cert_no', 'sex', 'place_of_birth', 'mother_tongue',
                'is_ip', 'ip_specify', 'is_4ps', 'household_id', 'has_disability', 'disability_type',
                'current_house_no', 'current_street', 'current_barangay', 'current_city', 'current_province', 'current_zip',
                'same_as_current', 'permanent_house_no', 'permanent_street', 'permanent_barangay', 'permanent_city', 'permanent_province', 'permanent_zip',
                'father_name', 'father_contact', 'mother_name', 'mother_contact', 'guardian_name', 'guardian_contact',
                'is_returning', 'last_grade_completed', 'last_sy_completed', 'last_school_attended', 'last_school_id',
            ]);
        });
    }
};