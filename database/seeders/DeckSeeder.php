<?php

namespace Database\Seeders;

use App\Models\Deck;
use App\Models\User;
use Illuminate\Database\Seeder;

class DeckSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::all()->each(function (User $user) {
            Deck::factory()->for($user)->count(20)->create();
        });
    }
}
