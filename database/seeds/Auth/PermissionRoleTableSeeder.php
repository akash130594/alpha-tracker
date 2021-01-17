<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

/**
 * Class PermissionRoleTableSeeder.
 */
class PermissionRoleTableSeeder extends Seeder
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

        // Create Roles
        $superAdmin = Role::create(['name' => config('access.users.super_admin_role')]);

        $admin = Role::create(['name' => config('access.users.admin_role')]);

        $team_lead = Role::create(['name' => config('access.users.team_lead_role')]);

        $project_manager = Role::create(['name' => config('access.users.default_role')]);


        $pm_permissions = config('access.users.pm_permissions');
        $team_lead_permissions = config('access.users.tl_permissions');
        $admin_permissions = config('access.users.admin_permissions');

        $beta_permission = ['access_beta_pm', 'access_beta_admin'];

        // Create Permissions
        $permissions = array_merge($pm_permissions, $team_lead_permissions, $admin_permissions, $beta_permission);

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        //Assign Permissions to project managers
        $project_manager->givePermissionTo($pm_permissions);


        //Assign Permissions to Team lead
        $team_lead->givePermissionTo($team_lead_permissions);
        //$team_lead->givePermissionTo($pm_permissions);

        // Assign Permissions to Admin
        $admin->givePermissionTo($admin_permissions);
        //$admin->givePermissionTo($team_lead_permissions);
        //$admin->givePermissionTo($pm_permissions);

        // ALWAYS GIVE SUPER ADMIN ROLE ALL PERMISSIONS
        $superAdmin->givePermissionTo(Permission::all());


        $this->enableForeignKeys();
    }
}
