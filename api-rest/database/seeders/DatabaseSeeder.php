<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;


class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            PermissionsSeeder::class,
        ]);
        
        User::truncate();
        User::factory(9)->create()
            ->each(function ($user) {
                $user->assignRole('player');
            });
        

        User::factory(1)->admin()->create()
            ->each(function ($user) {
                $user->assignRole('admin');
            });
    }

}
