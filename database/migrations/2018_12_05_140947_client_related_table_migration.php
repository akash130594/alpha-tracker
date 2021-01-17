<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ClientRelatedTableMigration extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable(config('sqltable.table_names.client'))) {
            Schema::create(config('sqltable.table_names.client'), function (Blueprint $table) {
                $table->increments('id');
                $table->string('code', 10);
                $table->string('name');
                $table->string('email')->nullable();
                $table->string('phone')->nullable();
                $table->string('website')->nullable();
                $table->string('cvars', 500)->nullable();
                $table->longText('additional_json_data')->nullable();
                $table->longText('setting_data')->nullable();
                $table->boolean('security_flag')->default(0);
                $table->boolean('redirector_flag')->default(0);
                $table->longText('redirector_screener_parameters')->nullable();
                $table->boolean('redirector_survey_type_flag')->default(0);
                $table->integer('redirect_study_type_id')->nullable();
                $table->boolean('status')->default(1);
                $table->integer('order')->default(10);
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists(config('sqltable.table_names.client'));
    }
}
