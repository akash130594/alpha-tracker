<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStudyTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('study_types', function (Blueprint $table) {
            $table->increments('id');
            $table->string('code', 10);
            $table->string('name', 255)->nullable();
            $table->string('description', 255)->nullable();
            $table->boolean('status')->default(1);
            $table->integer('order')->default(10);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('study_types');
    }
}
