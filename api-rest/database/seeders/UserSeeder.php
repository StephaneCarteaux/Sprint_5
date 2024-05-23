<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //User::truncate();
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
