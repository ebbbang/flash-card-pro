<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        if (app()->isProduction() === false) {
            $this->call([
                UserSeeder::class,
                DeckSeeder::class,
                CardSeeder::class,
            ]);
        }
    }
}
