<?php

namespace Feature\Decks;

use App\Livewire\Decks;
use App\Models\Deck;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class ShowTest extends TestCase
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
        $this->get(route('decks.show', $this->deck))->assertRedirect('/login');
    }

    public function test_authenticated_users_can_visit_the_show_screen(): void
    {
        $this->actingAs($this->user);

        $this->get(route('decks.show', $this->deck))->assertStatus(200);
    }

    public function test_user_can_only_view_their_decks(): void
    {
        $this->actingAs($this->user);

        $this->get(route('decks.show', Deck::factory()->create()))->assertStatus(403);
    }

    public function test_user_can_view_deck(): void
    {
        Livewire::actingAs($this->user)
            ->test(Decks\Show::class, ['deck' => $this->deck])
            ->assertSee($this->deck->name);
    }
}
