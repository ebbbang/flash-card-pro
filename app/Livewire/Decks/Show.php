<?php

namespace App\Livewire\Decks;

use App\Models\Deck;
use Livewire\Component;

class Show extends Component
{
    public Deck $deck;

    public function mount(Deck $deck): void
    {
        $this->deck = $deck;
    }
}
