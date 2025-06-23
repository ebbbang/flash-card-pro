<?php

namespace Tests\Feature\Controllers\Api\Card;

use App\Models\Card;
use App\Models\Deck;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CardControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Card::factory()
            ->for(
                Deck::factory()->create(['is_public' => true])
            )
            ->count(mt_rand(1, 3))
            ->create();

        Card::factory()
            ->for(
                Deck::factory()->create(['is_public' => false])
            )
            ->count(mt_rand(1, 3))
            ->create();
    }

    public function test_guests_receive_unauthorized(): void
    {
        $this->getJson('/api/cards')->assertUnauthorized();
    }

    public function test_authenticated_users_can_access_cards(): void
    {
        $this->actingAs(User::factory()->create());

        $card = Card::whereHas('deck', fn (Builder $query) => $query->public())->first();
        $count = Card::whereHas('deck', fn (Builder $query) => $query->public())->count();

        $this->getJson('/api/cards')
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
}
