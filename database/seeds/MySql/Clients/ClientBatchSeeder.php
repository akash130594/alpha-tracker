<?php

use Illuminate\Database\Seeder;

class ClientBatchSeeder extends Seeder
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

        $this->call(ClientTableSeeder::class);
        $this->call(ClientSecurityTypeTableSeeder::class);
        $this->call(ClientSecurityImplTableSeeder::class);

        $this->enableForeignKeys();
    }
}
