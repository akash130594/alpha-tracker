<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCollectionTraffics extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection(config('database.mongodb_primary', 'mongodb'))->create('traffics', function (Jenssegers\Mongodb\Schema\Blueprint $collection) {
            $collection->timestamps('started_at');
            $collection->timestamps();
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
            ->table('traffics', function (Jenssegers\Mongodb\Schema\Blueprint $collection)
            {
                $collection->drop();
            });
        Schema::enableForeignKeyConstraints();
    }
}
