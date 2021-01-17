<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProjectVendorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('project_vendors', function (Blueprint $table) {

            $table->increments('id');
            $table->integer('project_id')->unsigned();
            $table->string('project_code', 100)->nullable();
            $table->integer('vendor_id')->unsigned();
            $table->string('vendor_code', 10)->nullable();
            $table->text('spec_quota_ids')->nullable();
            $table->text('spec_quota_names')->nullable();
            $table->float('cpi')->nullable();
            $table->integer('quota')->nullable();
            $table->boolean('sy_excl_link_flag')->default(0);
            $table->text('syv_complete')->nullable();
            $table->text('syv_terminate')->nullable();
            $table->text('syv_quotafull')->nullable();
            $table->text('syv_qualityterm')->nullable();
            $table->integer('quota_completes')->nullable()->default(0);
            $table->integer('quota_remains')->nullable();
            $table->boolean('vendor_screener_excl_flag')->default(0);
            $table->boolean('global_screener')->default(0);
            $table->boolean('predefined_screener')->default(0);
            $table->boolean('custom_screener')->default(0);
            $table->boolean('is_active')->default(1);
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
        Schema::dropIfExists('project_vendors');
    }
}
