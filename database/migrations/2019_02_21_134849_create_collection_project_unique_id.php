<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCollectionProjectUniqueId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection(config('database.mongodb_primary', 'mongodb'))->create('project_unique_id', function (Jenssegers\Mongodb\Schema\Blueprint $collection) {
            //$collection->index('field_1');
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
        Schema::connection(config('database.mongodb_primary', 'mongodb'))
            ->table('project_unique_id', function (Jenssegers\Mongodb\Schema\Blueprint $collection)
            {
                $collection->drop();
            });
        Schema::enableForeignKeyConstraints();
    }
}
