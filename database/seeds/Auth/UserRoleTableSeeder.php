<?php

use App\Models\Auth\User;
use Illuminate\Database\Seeder;

/**
 * Class UserRoleTableSeeder.
 */
class UserRoleTableSeeder extends Seeder
{
    use DisableForeignKeys;

    /**
     * Run the database seed.
     *
     * @return void
     */
    public function run()
    {
        $this->disableForeignKeys();

        User::find(1)->assignRole(config('access.users.super_admin_role'));

        User::find(2)->assignRole(config('access.users.admin_role'), config('access.users.team_lead_role'), config('access.users.default_role'));

        User::find(3)->assignRole(config('access.users.team_lead_role'), config('access.users.default_role'));

        User::find(4)->assignRole(config('access.users.default_role'));

        $this->enableForeignKeys();
    }
}
