<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUploadsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('uploads', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('disk');
            $table->string('mime');
            $table->string('name');
            $table->unsignedInteger('size');
            $table->string('path');
            $table->foreignUuid('user_id')->nullable()->references('id')->on('users')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignUuid('project_id')->nullable()->references('id')->on('projects')->cascadeOnUpdate()->nullOnDelete();
            $table->timestampsTz();
        });
        Schema::table('users', function(Blueprint $table) {
            $table
                ->foreignUuid('custom_avatar_id')
                ->nullable()
                ->references('id')
                ->on('uploads')
                ->cascadeOnUpdate()
                ->nullOnDelete();
        });
        Schema::table('projects', function (Blueprint $table) {
            $table->dropColumn('avatar');
            $table
                ->foreignUuid('avatar_id')
                ->nullable()
                ->references('id')
                ->on('uploads')
                ->cascadeOnUpdate()
                ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function(Blueprint $table) {
            $table->dropColumn('custom_avatar_id');
        });
        Schema::table('projects', function (Blueprint $table) {
            $table->dropColumn('avatar_id');
            $table->string('avatar')->nullable();
        });
        Schema::dropIfExists('uploads');
    }
}
