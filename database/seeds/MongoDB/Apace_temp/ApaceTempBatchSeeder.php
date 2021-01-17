<?php

use Illuminate\Database\Seeder;

class ApaceTempBatchSeeder extends Seeder
{
    use DisableForeignKeys;
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->disableForeignKeys();

        //$this->call(ArchiveCollectionSeeder::class);
        //$this->call(ProjectUniqueIdSeeder::class);
        //$this->call(TrafficCollectionSeeder::class);
        /*$this->call(CountryMasterData::class);*/
        $this->enableForeignKeys();
    }
}
