<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClientSecurityTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(config('sqltable.table_names.client_security_types'), function (Blueprint $table) {
            $table->increments('id');
            $table->string('code', 10);
            $table->string('name');
            $table->longText('field_data')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists(config('sqltable.table_names.client_security_types'));
    }
}
