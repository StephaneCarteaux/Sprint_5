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

        //Create permissions with UUIDs
        Permission::create(['name' => 'view player list']);
        Permission::create(['name' => 'view player throw list']);
        Permission::create(['name' => 'delete player throw list']);
        Permission::create(['name' => 'delete players throw list']);
        Permission::create(['name' => 'view player success percentage']);
        Permission::create(['name' => 'view players success percentage']);

        // create roles and assign existing permissions
        $player = Role::create(['name' => 'player']);
        $player->givePermissionTo('view player throw list');
        $player->givePermissionTo('delete player throw list');
        $player->givePermissionTo('view player success percentage');

        // gets all permissions via Gate::before rule; see AuthServiceProvider
        $admin = Role::create(['name' => 'admin']); 
    }
}
