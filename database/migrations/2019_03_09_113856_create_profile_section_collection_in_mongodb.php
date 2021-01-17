<?php

use Illuminate\Support\Facades\Schema;
use Jenssegers\Mongodb\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProfileSectionCollectionInMongodb extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection(config('database.mongodb_primary', 'mongodb'))->create('profile_sections_master', function (Jenssegers\Mongodb\Schema\Blueprint $collection) {
            $collection->increments('id');
            $collection->index('general_name');
            $collection->index('type');
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
            ->table('profile_sections_master', function (Jenssegers\Mongodb\Schema\Blueprint $collection)
            {
                $collection->drop();
            });
        Schema::enableForeignKeyConstraints();
    }
}
