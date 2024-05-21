<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;
use Illuminate\Support\Str;

class PermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        //Create permissions
        Permission::create(['guard_name' => 'api', 'name' => 'view player list']);
        Permission::create(['guard_name' => 'api', 'name' => 'view player throw list']);
        Permission::create(['guard_name' => 'api', 'name' => 'delete player throw list']);
        Permission::create(['guard_name' => 'api', 'name' => 'delete players throw list']);
        Permission::create(['guard_name' => 'api', 'name' => 'view player success percentage']);
        Permission::create(['guard_name' => 'api', 'name' => 'view players success percentage']);

        // create roles and assign existing permissions
        $player = Role::create(['guard_name' => 'api', 'name' => 'player']);
        $player->givePermissionTo('view player throw list');
        $player->givePermissionTo('delete player throw list');
        $player->givePermissionTo('view player success percentage');

        // gets all permissions via Gate::before rule; see AuthServiceProvider
        Role::create(['name' => 'admin']); 


    }
}
