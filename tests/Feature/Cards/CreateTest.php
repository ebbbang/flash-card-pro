<?php

namespace Tests\Feature\Cards;

use App\Livewire\Cards;
use App\Models\Card;
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

    private Deck $deck;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        $this->deck = Deck::factory()->for($this->user)->create();
    }

    public function test_guests_are_redirected_to_the_login_page(): void
    {
        $this->get("/decks/{$this->deck->id}/cards/create")->assertRedirect('/login');
    }

    public function test_authenticated_users_can_visit_the_create_screen(): void
    {
        $this->actingAs($this->user);

        $this->get("/decks/{$this->deck->id}/cards/create")->assertStatus(200);
    }

    public function test_user_cannot_visit_other_users_create_screen(): void
    {
        $this->actingAs(User::factory()->create());

        $this->get("/decks/{$this->deck->id}/cards/create")->assertStatus(403);
    }

    public function test_user_can_create_card_with_valid_data(): void
    {
        Livewire::actingAs($this->user)
            ->test(Cards\Create::class, [$this->deck])
            ->set('question', $question = Str::random())
            ->set('answer', $answer = Str::random())
            ->call('store')
            ->assertHasNoErrors()
            ->assertRedirect(route('cards.index', $this->deck, absolute: false));

        $this->assertDatabaseHas('cards', [
            'deck_id' => $this->deck->id,
            'question' => $question,
            'answer' => $answer,
        ]);
    }

    public function test_user_can_not_create_card_with_invalid_data(): void
    {
        $existingCard = Card::factory()->for($this->deck)->create();
        $count = $this->deck->cards()->count();

        Livewire::actingAs($this->user)
            ->test(Cards\Create::class, [$this->deck])
            ->call('store')
            ->assertHasErrors([
                'question' => 'required',
                'answer' => 'required',
            ]);

        Livewire::actingAs($this->user)
            ->test(Cards\Create::class, [$this->deck])
            ->set('question', ' ')
            ->set('answer', ' ')
            ->call('store')
            ->assertHasErrors([
                'question' => 'required',
                'answer' => 'required',
            ]);

        Livewire::actingAs($this->user)
            ->test(Cards\Create::class, [$this->deck])
            ->set('question', Str::random(256))
            ->set('answer', Str::random(256))
            ->call('store')
            ->assertHasErrors([
                'question' => 'max',
                'answer' => 'max',
            ]);

        Livewire::actingAs($this->user)
            ->test(Cards\Create::class, [$this->deck])
            ->set('question', $existingCard->question)
            ->set('answer', Str::random())
            ->call('store')
            ->assertHasErrors(['question' => 'unique']);

        $this->assertSame($count, $this->user->decks()->count());
    }
}
