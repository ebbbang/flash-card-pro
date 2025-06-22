<?php

namespace App\Livewire\Studies;

use App\Models\Card;
use App\Models\Deck;
use Illuminate\Contracts\Support\Renderable;
use Livewire\Attributes\Computed;
use Livewire\Component;

class Index extends Component
{
    public Deck $deck;

    public array $cards;

    public int $totalCards;

    public int $currentCardIndex = 0;

    public function mount(Deck $deck): void
    {
        $this->deck = $deck;

        $this->cards = $this->deck
            ->cards
            ->map(fn (Card $card) => [
                'question' => $card->question,
                'answer' => $card->answer,
                'answerRevealed' => false,
                'answeredCorrectly' => false,
            ])
            ->toArray();

        $this->totalCards = count($this->cards);
    }

    #[Computed]
    public function currentCard(): array
    {
        return $this->cards[$this->currentCardIndex];
    }

    #[Computed]
    public function score(): int
    {
        $score = 0;

        foreach ($this->cards as $card) {
            if ($card['answeredCorrectly']) {
                $score++;
            }
        }

        return $score;
    }

    #[Computed]
    public function progress(): float
    {
        return ($this->currentCardIndex * 100) / $this->totalCards;
    }

    public function render(): Renderable
    {
        return view('livewire.studies.index');
    }

    public function revealAnswer(): void
    {
        $this->cards[$this->currentCardIndex]['answerRevealed'] = true;
    }

    public function setAnsweredCorrectly(bool $answeredCorrectly): void
    {
        $this->cards[$this->currentCardIndex]['answeredCorrectly'] = $answeredCorrectly;

        $this->currentCardIndex++;
    }
}
