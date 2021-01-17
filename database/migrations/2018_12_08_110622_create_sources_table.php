<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSourcesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sources', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('source_type_id')->unsigned();
            $table->string('code', 10)->nullable();
            $table->string('name', 255)->nullable();
            $table->string('email', 255)->nullable();
            $table->string('phone', 50)->nullable();
            $table->string('vvars', 500)->nullable();
            $table->string('complete_url', 600)->nullable();
            $table->string('terminate_url', 600)->nullable();
            $table->string('quotafull_url', 600)->nullable();
            $table->string('quality_term_url', 600)->nullable();
            $table->longText('extra_url')->nullable();
            $table->boolean('validation_status')->default(0);
            $table->string('algo', 20)->nullable();
            $table->string('secret_key', 50)->nullable();
            $table->string('parameter_name', 50)->nullable();
            $table->boolean('global_screener')->default(0);
            $table->boolean('defined_screener')->default(0);
            $table->boolean('custom_screener')->default(0);
            $table->boolean('pre_selected')->default(0);
            $table->boolean('status')->default(0);
            $table->tinyInteger('is_api')->default(0);
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
        Schema::dropIfExists('sources');
    }
}
