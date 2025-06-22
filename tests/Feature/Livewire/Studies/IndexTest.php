<?php

namespace Tests\Feature\Livewire\Studies;

use App\Livewire\Studies;
use App\Models\Card;
use App\Models\Deck;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class IndexTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    private Deck $deck;

    private Collection $cards;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        $this->deck = Deck::factory()->for($this->user)->create();
        $this->cards = Card::factory()->for($this->deck)->count(2)->create();
    }

    public function test_guests_are_redirected_to_the_login_page(): void
    {
        $this->get("/decks/{$this->deck->id}/studies")->assertRedirect('/login');
    }

    public function test_authenticated_users_can_visit_the_index_screen(): void
    {
        $this->actingAs($this->user);

        $this->get("/decks/{$this->deck->id}/studies")->assertOk();
    }

    public function test_user_can_only_visit_public_deck_from_other_users(): void
    {
        $this->actingAs(User::factory()->create());

        $deck = Deck::factory()->create(['is_public' => false]);
        $this->get("/decks/{$deck->id}/studies")->assertStatus(403);

        $deck = Deck::factory()->create(['is_public' => true]);
        $this->get("/decks/{$deck->id}/studies")->assertOk();
    }

    public function test_user_can_see_cards(): void
    {
        Livewire::actingAs($this->user)
            ->test(Studies\Index::class, [$this->deck])
            ->assertViewHas('cards', function (array $cards) {
                return count($cards) === 2;
            });
    }

    public function test_user_can_reveal_answer(): void
    {
        Livewire::actingAs($this->user)
            ->test(Studies\Index::class, [$this->deck])
            ->call('revealAnswer')
            ->assertViewHas('cards', function (array $cards) {
                return $cards[0]['answerRevealed'] === true;
            });
    }

    public function test_user_can_set_answered_correctly(): void
    {
        Livewire::actingAs($this->user)
            ->test(Studies\Index::class, [$this->deck])
            ->call('setAnsweredCorrectly', true)
            ->assertViewHas('cards', function (array $cards) {
                return $cards[0]['answeredCorrectly'] === true;
            })
            ->assertViewHas('currentCardIndex', function (int $currentCardIndex) {
                return $currentCardIndex === 1;
            })
            ->call('setAnsweredCorrectly', false)
            ->assertViewHas('cards', function (array $cards) {
                return $cards[1]['answeredCorrectly'] === false;
            })
            ->assertViewHas('currentCardIndex', function (int $currentCardIndex) {
                return $currentCardIndex === 2;
            });
    }

    public function test_user_can_see_score_at_the_end(): void
    {
        Livewire::actingAs($this->user)
            ->test(Studies\Index::class, [$this->deck])
            ->call('setAnsweredCorrectly', true)
            ->call('setAnsweredCorrectly', false)
            ->assertSee('Score: 1 / 2');

    }
}
