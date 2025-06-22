<?php

namespace App\Livewire\Cards;

use App\Models\Card;
use App\Models\Deck;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Support\Renderable;
use Livewire\Component;

class Index extends Component
{
    public Deck $deck;

    public function mount(Deck $deck): void
    {
        $this->deck = $deck;
    }

    public function render(): Renderable
    {
        return view('livewire.cards.index', [
            'cards' => $this->deck->cards()->latest('id')->paginate(),
        ]);
    }

    /**
     * @throws AuthorizationException
     */
    public function delete(Card $card): void
    {
        $this->authorize('delete', $card);

        $card->delete();
    }
}
