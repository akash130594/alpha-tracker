<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProjectQuotasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('project_quotas', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('project_id')->unsigned();
            $table->string('name')->nullable();
            $table->text('description')->nullable();
            $table->float('cpi')->nullable();
            $table->integer('count')->nullable();
            $table->longText('raw_quota_spec')->nullable();
            $table->longText('formatted_quota_spec')->nullable();
            $table->string('type')->nullable();
            $table->boolean('status')->default(1);
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
        Schema::dropIfExists('project_quotas');
        Schema::enableForeignKeyConstraints();
    }
}
