<?php

namespace Feature\Decks;

use App\Livewire\Decks;
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

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
    }

    public function test_guests_are_redirected_to_the_login_page(): void
    {
        $this->get('/decks')->assertRedirect('/login');
    }

    public function test_authenticated_users_can_visit_the_index_screen(): void
    {
        $this->actingAs($this->user);

        $this->get('/decks')->assertStatus(200);
    }

    public function test_user_can_see_their_decks(): void
    {
        Deck::factory()->for($this->user)->count(20)->create();

        $firstPageDeck = $this->user->decks()->latest('id')->first();
        $secondPageDeck = $this->user->decks()->latest('id')->skip(15)->first();

        Livewire::actingAs($this->user)
            ->test(Decks\Index::class)
            ->assertViewHas('decks', function (LengthAwarePaginator $decks) {
                return $decks->count() === 15 && $decks->total() === $this->user->decks()->count();
            })
            ->assertSee($firstPageDeck->name)
            ->assertDontSee($secondPageDeck->name);

        Livewire::actingAs($this->user)
            ->withQueryParams(['page' => 2])
            ->test(Decks\Index::class)
            ->assertViewHas('decks', function (LengthAwarePaginator $decks) {
                return $decks->count() === 5 && $decks->total() === $this->user->decks()->count();
            })
            ->assertSee($secondPageDeck->name)
            ->assertDontSee($firstPageDeck->name);

        Livewire::actingAs(User::factory()->create())
            ->test(Decks\Index::class)
            ->assertViewHas('decks', function (LengthAwarePaginator $decks) {
                return $decks->count() === 0 && $decks->total() === 0;
            });
    }
}
