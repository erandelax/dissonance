<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateConfigsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('configs', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table
                ->foreignUuid('project_id')
                ->nullable()
                ->index()
                ->references('id')
                ->on('projects')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
            $table->string('type');
            $table->jsonb('data');
            $table->timestampsTz();
            $table->unique(['project_id', 'type']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('settings');
    }
}
