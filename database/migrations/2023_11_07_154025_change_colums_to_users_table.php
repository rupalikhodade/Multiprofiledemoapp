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
        Schema::table('users', function (Blueprint $table) {
            $table->string('username',50)->nullable()->after('email')->unique();
            $table->string('name',50)->change();
            $table->string('email',100)->change();
            $table->string('password',200)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {   
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
};
