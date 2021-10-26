<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePageRevisionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('page_revisions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('item_id')->references('id')->on('pages')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignUuid('user_id')->nullable()->references('id')->on('users')->cascadeOnDelete()->cascadeOnUpdate();
            $table->jsonb('data');
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
        Schema::dropIfExists('page_revisions');
    }
}
