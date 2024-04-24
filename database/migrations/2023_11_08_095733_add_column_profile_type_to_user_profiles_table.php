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
        Schema::table('user_profiles', function (Blueprint $table) {
            $table->tinyInteger('profile_type')->unsigned()->nullable()->default(0)->after('email')->comment('1:personal, 2: professional');
        });

        Schema::table('users', function($table) {
            $table->dropColumn('email');
            $table->dropColumn('name');
            $table->dropColumn('email_verified_at');
            $table->string('first_name',50)->nullable()->after('username');
            $table->string('last_name',50)->nullable()->after('first_name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_profiles', function (Blueprint $table) {
            $table->dropColumn('profile_type');
        });
    }
};
