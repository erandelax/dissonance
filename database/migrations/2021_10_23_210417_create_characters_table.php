<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCharactersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('characters', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('user_id')->nullable()->references('id')->on('users')->cascadeOnDelete()->cascadeOnUpdate();
            $table->string('player_name', 255)->nullable();
            $table->unsignedBigInteger('player_id')->nullable();
            $table->string('name');
            $table->tinyInteger('status')->default(0);
            $table->jsonb('data');
            $table->timestamps();
        });
        Schema::table('users', function (Blueprint $table) {
            $table->foreignUuid('character_id')->index()->nullable()->references('id')->on('characters')->cascadeOnUpdate()->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('character_id');
        });
        Schema::dropIfExists('characters');
    }
}
