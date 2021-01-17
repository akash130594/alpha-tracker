<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProjectSurveysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('project_surveys', function (Blueprint $table) {

            $table->increments('id');
            $table->string('code', 100);
            $table->integer('project_vendor_id')->unsigned();
            $table->integer('project_id')->unsigned();
            $table->string('project_code', 100);
            $table->integer('vendor_id')->unsigned();
            $table->string('vendor_code', 10);
            $table->string('vendor_survey_code')->nullable();
            $table->text('survey_live_url')->nullable();
            $table->text('survey_test_url')->nullable();
            $table->boolean('sy_excl_link_flag')->default(0);
            $table->text('syv_complete')->nullable();
            $table->text('syv_terminate')->nullable();
            $table->text('syv_quotafull')->nullable();
            $table->text('syv_qualityterm')->nullable();
            $table->text('syv_other_url')->nullable();
            $table->boolean('collection_dedupe')->default(0);
            $table->text('collection_ids')->nullable();
            $table->text('dedupe_status')->nullable();
            $table->integer('status_id')->unsigned();
            $table->string('status_label')->nullable();



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
        Schema::dropIfExists('project_surveys');
    }
}
