<?php

namespace App\Livewire\Cards;

use App\Models\Card;
use App\Models\Deck;
use Illuminate\Validation\Rule;
use Livewire\Component;

class Edit extends Component
{
    public Deck $deck;

    public Card $card;

    public string $question = '';

    public string $answer = '';

    public function mount(Deck $deck, Card $card): void
    {
        if ($card->deck_id !== $deck->id) {
            abort(404);
        }

        $this->deck = $deck;
        $this->card = $card;
        $this->question = $card->question;
        $this->answer = $card->answer;
    }

    protected function rules(): array
    {
        return [
            'question' => [
                'required',
                'string',
                'max:255',
                Rule::unique('cards', 'question')->where('deck_id', $this->deck->id)->ignore($this->card->id),
            ],
            'answer' => [
                'required',
                'string',
                'max:255',
            ],
        ];
    }

    public function update(): void
    {
        $validated = $this->validate();

        $this->card->update($validated);

        $this->redirect(route('cards.index', $this->deck, absolute: false), navigate: true);
    }
}
