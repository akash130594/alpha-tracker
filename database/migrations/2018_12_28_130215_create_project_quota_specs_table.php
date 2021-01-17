<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProjectQuotaSpecsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('project_quota_specs', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('project_quota_id')->unsigned();
            $table->boolean('is_global')->default(0);
            $table->string('question_general_name', 255)->nullable();
            $table->integer('question_id')->unsigned()->nullable();
            $table->string('type')->nullable();
            $table->longText('values')->nullable();
            $table->longText('raw_spec')->nullable();

            $table->foreign('project_quota_id')->references('id')->on('project_quotas')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('project_quota_specs');
        Schema::enableForeignKeyConstraints();
    }
}
