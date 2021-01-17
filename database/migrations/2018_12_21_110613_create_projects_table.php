<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->increments('id');

            $table->string('code', 100);
            $table->string('name', 255);
            $table->string('label', 255)->nullable();
            $table->integer('study_type_id')->unsigned()->default(1);
            $table->integer('project_topic_id')->unsigned()->default(1);
            $table->integer('collects_pii')->unsigned()->default(0);
            $table->integer('client_id')->unsigned();
            $table->string('client_code', 10)->nullable();
            $table->string('client_name', 255);
            $table->string('client_var', 100)->nullable();
            $table->text('client_link')->nullable();
            $table->string('client_project_no', 100)->nullable();
            $table->boolean('unique_ids_flag')->default(0);
            $table->text('unique_ids_file')->nullable();
            $table->tinyInteger('can_links')->default(0);
            $table->integer('country_id')->unsigned();
            $table->string('country_code', 10)->nullable();
            $table->integer('language_id')->unsigned();
            $table->string('language_code', 10)->nullable();
            $table->dateTime('start_date')->nullable();
            $table->dateTime('end_date')->nullable();
            $table->integer('created_by')->unsigned();
            $table->integer('updated_by')->unsigned()->nullable();
            $table->float('ir', 8, 2)->default(0);
            $table->integer('loi')->default(1);
            $table->boolean('loi_validation')->default(0);
            $table->integer('loi_validation_time')->default(0);
            $table->boolean('client_screener_redirect_flag')->default(0);
            $table->longText('client_screener_redirect_data')->nullable();
            $table->float('cpi', 8, 2)->default(0);
            $table->float('incentive', 8, 2)->default(0);
            $table->integer('quota');
            $table->boolean('survey_dedupe_flag')->default(0);
            $table->integer('survey_dedupe_list_id')->unsigned();
            $table->integer('status_id')->unsigned();
            $table->string('status_label', 100)->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('projects');
    }
}
