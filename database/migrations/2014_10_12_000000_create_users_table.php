<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('locale');
            $table->string('name');
            $table->string('nickname')->nullable();
            $table->string('avatar')->nullable();
            $table->string('banner')->nullable();
            $table->string('banner_color')->nullable();
            $table->string('accent_color')->nullable();
            $table->string('discord_id')->nullable()->unique();
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('auth_token')->nullable();
            $table->string('timezone')->nullable();
            $table->rememberToken();
            $table->timestampsTz();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
