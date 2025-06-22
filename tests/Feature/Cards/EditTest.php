<?php

namespace Feature\Cards;

use App\Livewire\Cards;
use App\Models\Card;
use App\Models\Deck;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Livewire\Livewire;
use Tests\TestCase;

class EditTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    private Deck $deck;

    private Card $card;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        $this->deck = Deck::factory()->for($this->user)->create();
        $this->card = Card::factory()->for($this->deck)->create();
    }

    public function test_guests_are_redirected_to_the_login_page(): void
    {
        $this->get("/decks/{$this->deck->id}/cards/{$this->card->id}/edit")->assertRedirect('/login');
    }

    public function test_authenticated_users_can_visit_the_edit_screen(): void
    {
        $this->actingAs($this->user);

        $this->get("/decks/{$this->deck->id}/cards/{$this->card->id}/edit")->assertStatus(200);
    }

    public function test_user_cannot_visit_other_users_edit_screen(): void
    {
        $this->actingAs(User::factory()->create());

        $this->get("/decks/{$this->deck->id}/cards/{$this->card->id}/edit")->assertStatus(403);
    }

    public function test_user_cannot_visit_other_decks_edit_screen(): void
    {
        $deck = Deck::factory()->for($this->user)->create();

        Livewire::actingAs($this->user)
            ->test(Cards\Edit::class, [$deck, $this->card])
            ->assertStatus(404);
    }

    public function test_user_can_edit_card_with_valid_data(): void
    {
        Livewire::actingAs($this->user)
            ->test(Cards\Edit::class, [$this->deck, $this->card])
            ->set('question', $question = Str::random())
            ->set('answer', $answer = Str::random())
            ->call('update')
            ->assertHasNoErrors()
            ->assertRedirect(route('cards.index', $this->deck, absolute: false));

        $this->assertDatabaseHas('cards', [
            'id' => $this->card->id,
            'deck_id' => $this->deck->id,
            'question' => $question,
            'answer' => $answer,
        ]);
    }

    public function test_user_can_not_edit_card_with_invalid_data(): void
    {
        $existingCard = Card::factory()->for($this->deck)->create();

        Livewire::actingAs($this->user)
            ->test(Cards\Edit::class, [$this->deck, $this->card])
            ->set('question', ' ')
            ->set('answer', ' ')
            ->call('update')
            ->assertHasErrors([
                'question' => 'required',
                'answer' => 'required',
            ]);

        Livewire::actingAs($this->user)
            ->test(Cards\Edit::class, [$this->deck, $this->card])
            ->set('question', Str::random(256))
            ->set('answer', Str::random(256))
            ->call('update')
            ->assertHasErrors([
                'question' => 'max',
                'answer' => 'max',
            ]);

        Livewire::actingAs($this->user)
            ->test(Cards\Edit::class, [$this->deck, $this->card])
            ->set('question', $existingCard->question)
            ->set('answer', Str::random())
            ->call('update')
            ->assertHasErrors(['question' => 'unique']);

        $this->assertDatabaseHas('cards', [
            'id' => $this->card->id,
            'deck_id' => $this->deck->id,
            'question' => $this->card->question,
            'answer' => $this->card->answer,
        ]);
    }
}
