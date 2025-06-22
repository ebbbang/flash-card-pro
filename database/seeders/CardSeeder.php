<?php

namespace Database\Seeders;

use App\Models\Card;
use App\Models\Deck;
use Illuminate\Database\Seeder;

class CardSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Deck::all()->each(function (Deck $deck) {
            Card::factory()->for($deck)->count(mt_rand(16, 20))->create();
        });
    }
}
