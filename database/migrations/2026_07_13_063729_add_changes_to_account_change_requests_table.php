<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('account_change_requests', function (Blueprint $table) {
            $table->json('changes')->nullable()->after('new_password');
        });
    }

    public function down(): void
    {
        Schema::table('account_change_requests', function (Blueprint $table) {
            $table->dropColumn('changes');
        });
    }
};