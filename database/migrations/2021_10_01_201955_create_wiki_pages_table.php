<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWikiPagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wiki_pages', function (Blueprint $table) {
            $table->uuid('guid')->primary();
            $table->string('locale');
            $table->string('slug');
            $table->string('title')->default('');
            $table->text('content')->default('');
            $table->jsonb('refs')->default('[]');
            $table->timestamps();

            $table->index(['locale','slug']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('wiki_pages');
    }
}
