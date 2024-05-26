<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;


class PassportSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ejecutar el comando para crear un cliente personal de Passport
        Artisan::call('passport:client', [
            '--personal' => true,
            '--no-interaction' => true,
        ]);
    }
}
