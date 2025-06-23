<?php

namespace Tests\Feature\Controllers\Api\Deck;

use App\Models\Card;
use App\Models\Deck;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CardControllerTest extends TestCase
{
    use RefreshDatabase;

    private Deck $deck;

    protected function setUp(): void
    {
        parent::setUp();

        $this->deck = Deck::factory()->create(['is_public' => true]);
        Card::factory()->for($this->deck)->count(mt_rand(1, 3))->create();
    }

    public function test_guests_receive_unauthorized(): void
    {
        $this->getJson("/api/decks/{$this->deck->id}/cards")->assertUnauthorized();
    }

    public function test_authenticated_users_can_access_cards(): void
    {
        $this->actingAs(User::factory()->create());

        $card = $this->deck->cards()->first();
        $count = $this->deck->cards()->count();

        $this->getJson("/api/decks/{$this->deck->id}/cards")
            ->assertOk()
            ->assertJsonStructure(['data', 'links', 'meta'])
            ->assertJsonCount(min(15, $count), 'data')
            ->assertJsonCount(4, 'links')
            ->assertJsonCount(8, 'meta')
            ->assertJsonFragment([
                'id' => $card->id,
                'question' => $card->question,
                'answer' => $card->answer,
                'created_at' => $card->created_at->toIso8601String(),
                'updated_at' => $card->updated_at->toIso8601String(),
            ]);
    }

    public function test_only_provides_cards_from_public_decks(): void
    {
        $this->actingAs(User::factory()->create());

        $deck = Deck::factory()->create(['is_public' => false]);

        $this->getJson("/api/decks/{$deck->id}/cards")
            ->assertForbidden();
    }
}
