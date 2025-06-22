<?php

namespace Tests\Feature\Livewire\Decks;

use App\Livewire\Decks;
use App\Models\Deck;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Livewire\Livewire;
use Tests\TestCase;

class CreateTest extends TestCase
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
        $this->get('/decks/create')->assertRedirect('/login');
    }

    public function test_authenticated_users_can_visit_the_create_screen(): void
    {
        $this->actingAs($this->user);

        $this->get('/decks/create')->assertOk();
    }

    public function test_user_can_create_deck_with_valid_data(): void
    {
        Livewire::actingAs($this->user)
            ->test(Decks\Create::class)
            ->set('name', $name = Str::random())
            ->set('is_public', $isPublic = mt_rand(0, 1))
            ->call('store')
            ->assertHasNoErrors()
            ->assertRedirect(route('decks.index', absolute: false));

        $this->assertDatabaseHas('decks', [
            'user_id' => $this->user->id,
            'name' => $name,
            'is_public' => $isPublic,
        ]);
    }

    public function test_user_can_not_create_deck_with_invalid_data(): void
    {
        $existingDeck = Deck::factory()->for($this->user)->create();
        $count = $this->user->decks()->count();

        Livewire::actingAs($this->user)
            ->test(Decks\Create::class)
            ->call('store')
            ->assertHasErrors([
                'name' => 'required',
                'is_public' => 'required',
            ]);

        Livewire::actingAs($this->user)
            ->test(Decks\Create::class)
            ->set('name', ' ')
            ->call('store')
            ->assertHasErrors(['name' => 'required']);

        Livewire::actingAs($this->user)
            ->test(Decks\Create::class)
            ->set('name', Str::random(21))
            ->call('store')
            ->assertHasErrors(['name' => 'max']);

        Livewire::actingAs($this->user)
            ->test(Decks\Create::class)
            ->set('name', $existingDeck->name)
            ->call('store')
            ->assertHasErrors(['name' => 'unique']);

        $this->assertSame($count, $this->user->decks()->count());
    }
}
