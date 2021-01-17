<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProjectDedupesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('project_dedupes', function (Blueprint $table) {
            $table->increments('id');
            $table->string('dedupe_status')->default('attempted');
            $table->longText('dedupe_data')->nullable();
            $table->longText('dedupe_selected_filter')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('project_dedupes');
    }
}
