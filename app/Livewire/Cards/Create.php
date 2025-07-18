<?php

namespace App\Livewire\Cards;

use App\Models\Deck;
use Illuminate\Validation\Rule;
use Livewire\Component;

class Create extends Component
{
    public Deck $deck;

    public string $question = '';

    public string $answer = '';

    public function mount(Deck $deck): void
    {
        $this->deck = $deck;
    }

    protected function rules(): array
    {
        return [
            'question' => [
                'required',
                'string',
                'max:255',
                Rule::unique('cards', 'question')->where('deck_id', $this->deck->id),
            ],
            'answer' => [
                'required',
                'string',
                'max:255',
            ],
        ];
    }

    public function store(): void
    {
        $validated = $this->validate();

        $this->deck->cards()->create($validated);

        $this->redirect(route('cards.index', $this->deck, absolute: false), navigate: true);
    }
}
