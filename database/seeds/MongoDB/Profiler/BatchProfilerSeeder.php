<?php

use Illuminate\Database\Seeder;

class BatchProfilerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(ProfileSectionCollectionSeeder::class);
        $this->call(VendorMappingSeeder::class);
    }
}
