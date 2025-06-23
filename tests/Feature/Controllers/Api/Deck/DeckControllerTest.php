<?php

namespace Tests\Feature\Controllers\Api\Deck;

use App\Models\Card;
use App\Models\Deck;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DeckControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_guests_receive_unauthorized(): void
    {
        $this->getJson('/api/decks')->assertUnauthorized();
    }

    public function test_authenticated_users_can_access_decks(): void
    {
        $this->actingAs(User::factory()->create());

        $deck = Deck::factory()->count(mt_rand(1, 3))->create(['is_public' => true])->first();
        $count = Deck::public()->count();
        Card::factory()->for($deck)->count(mt_rand(1, 3))->create();

        $this->getJson('/api/decks')
            ->assertOk()
            ->assertJsonStructure(['data', 'links', 'meta'])
            ->assertJsonCount(min(15, $count), 'data')
            ->assertJsonCount(4, 'links')
            ->assertJsonCount(8, 'meta')
            ->assertJsonFragment([
                'id' => $deck->id,
                'name' => $deck->name,
                'cards_count' => $deck->cards()->count(),
                'created_at' => $deck->created_at->toIso8601String(),
                'updated_at' => $deck->updated_at->toIso8601String(),
            ]);
    }

    public function test_only_provides_public_decks(): void
    {
        $this->actingAs(User::factory()->create());

        Deck::factory()->count(mt_rand(1, 3))->create(['is_public' => false]);
        Deck::factory()->count(mt_rand(1, 3))->create(['is_public' => true]);

        $count = Deck::public()->count();

        $this->getJson('/api/decks')
            ->assertOk()
            ->assertJsonCount($count, 'data');
    }
}
