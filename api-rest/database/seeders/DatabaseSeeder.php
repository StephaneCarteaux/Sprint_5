<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\PermissionServiceProvider;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::truncate();
        User::factory(9)->create();
        User::factory(1)->admin()->create();

        $this->call([
            PermissionsSeeder::class,
        ]);
    }

}
