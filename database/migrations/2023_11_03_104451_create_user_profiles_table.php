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
        Schema::create('user_profiles', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->unsigned();
            $table->string('email',100)->nullable();
            $table->string('profile_picture', 50)->nullable();
            $table->string('contact',20)->nullable();
            $table->tinyText('address')->nullable();
            $table->string('company_name',100)->nullable();
            $table->string('professional_role',50)->nullable();
            $table->string('experience',10)->nullable();
            $table->tinyInteger('is_default_profile')->unsigned()->nullable()->default(0)->comment('0:not default, 1: default');
            $table->tinyInteger('is_deleted')->unsigned()->nullable()->default(0)->comment('0:Not deleted, 1: Deleted');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');;
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_profiles');
    }
};
