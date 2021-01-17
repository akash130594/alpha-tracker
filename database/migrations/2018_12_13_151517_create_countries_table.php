<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCountriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('countries', function (Blueprint $table) {
            $table->increments('id');
            $table->string('country_code', 3);
            $table->string('name', 255)->nullable();
            $table->string('capital', 255)->nullable();
            $table->string('iso_3166_2', 2)->nullable();
            $table->string('iso_3166_3', 3)->nullable();
            $table->string('currency_code', 100)->nullable();
            $table->string('currency_symbol', 50)->nullable();
            $table->integer('currency_decimals')->default(0);
            $table->string('citizenship', 50)->nullable();
            $table->string('calling_code', 50)->nullable();
            $table->string('flag', 50)->nullable();
            $table->string('language', 255)->nullable();
            $table->string('default_locale', 50)->nullable();
            $table->boolean('status')->default(1);
            $table->boolean('is_filterable')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('countries');
    }
}
