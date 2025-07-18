<?php

namespace Tests\Feature\Livewire\Cards;

use App\Livewire\Cards;
use App\Models\Card;
use App\Models\Deck;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Pagination\LengthAwarePaginator;
use Livewire\Livewire;
use Tests\TestCase;

class IndexTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    private Deck $deck;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        $this->deck = Deck::factory()->for($this->user)->create();
    }

    public function test_guests_are_redirected_to_the_login_page(): void
    {
        $this->get("/decks/{$this->deck->id}/cards")->assertRedirect('/login');
    }

    public function test_authenticated_users_can_visit_the_index_screen(): void
    {
        $this->actingAs($this->user);

        $this->get("/decks/{$this->deck->id}/cards")->assertOk();
    }

    public function test_user_can_only_visit_public_deck_from_other_users(): void
    {
        $this->actingAs(User::factory()->create());

        $deck = Deck::factory()->create(['is_public' => false]);
        $this->get("/decks/{$deck->id}/cards")->assertStatus(403);

        $deck = Deck::factory()->create(['is_public' => true]);
        $this->get("/decks/{$deck->id}/cards")->assertOk();
    }

    public function test_user_can_see_the_deck_cards(): void
    {
        Card::factory()->for($this->deck)->count(20)->create();

        $firstPageCard = $this->deck->cards()->latest('id')->first();
        $secondPageCard = $this->deck->cards()->latest('id')->skip(15)->first();

        Livewire::actingAs($this->user)
            ->test(Cards\Index::class, [$this->deck])
            ->assertViewHas('cards', function (LengthAwarePaginator $cards) {
                return $cards->count() === 15 && $cards->total() === $this->deck->cards()->count();
            })
            ->assertSee($firstPageCard->name)
            ->assertDontSee($secondPageCard->name);

        Livewire::actingAs($this->user)
            ->withQueryParams(['page' => 2])
            ->test(Cards\Index::class, [$this->deck])
            ->assertViewHas('cards', function (LengthAwarePaginator $cards) {
                return $cards->count() === 5 && $cards->total() === $this->deck->cards()->count();
            })
            ->assertSee($secondPageCard->name)
            ->assertDontSee($firstPageCard->name);

        Livewire::actingAs($otherUser = User::factory()->create())
            ->test(Cards\Index::class, [Deck::factory()->for($otherUser)->create()])
            ->assertViewHas('cards', function (LengthAwarePaginator $cards) {
                return $cards->count() === 0 && $cards->total() === 0;
            });
    }

    public function test_user_can_delete_a_card(): void
    {
        $card = Card::factory()->for($this->deck)->create();

        Livewire::actingAs($this->user)
            ->test(Cards\Index::class, [$this->deck])
            ->assertSee($card->question)
            ->call('delete', $card)
            ->assertDontSee($card->question);

        $this->assertDatabaseMissing('cards', ['id' => $card->id]);
    }

    public function test_user_cannot_delete_another_user_card(): void
    {
        $card = Card::factory()->for($this->deck)->create();

        Livewire::actingAs(User::factory()->create())
            ->test(Cards\Index::class, [$this->deck])
            ->call('delete', $card)
            ->assertStatus(403);

        $this->assertDatabaseHas('cards', ['id' => $card->id]);
    }
}
