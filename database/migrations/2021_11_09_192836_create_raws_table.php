<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRawsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('raws', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('type')->default('text/html');
            $table->string('uri', 2048)->nullable()->index();
            $table->longText('body')->nullable();
            $table
                ->foreignUuid('user_id')
                ->nullable()
                ->index()
                ->references('id')
                ->on('users')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
            $table->string('locale');
            $table
                ->foreignUuid('project_id')
                ->nullable()
                ->index()
                ->references('id')
                ->on('projects')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
            $table->unique(['project_id', 'uri']);
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
        Schema::dropIfExists('raws');
    }
}
