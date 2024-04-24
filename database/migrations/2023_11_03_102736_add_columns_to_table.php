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
            $table->date('birth_date')->nullable()->after('password');
            $table->tinyInteger('gender')->unsigned()->nullable()->default(0)->comment('0:Undefined,1:Male,2:Female,3:Transgender')->after('birth_date');
            $table->tinyInteger('status')->unsigned()->nullable()->default(1)->comment('0:Inactive,1:Active')->after('gender');
            $table->tinyInteger('is_deleted')->unsigned()->nullable()->default(0)->comment('0:Not deleted, 1:Deleted')->after('status');
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['birth_date', 'gender', 'status','is_deleted']);
        });
    }
};
