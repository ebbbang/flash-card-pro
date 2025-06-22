<?php

namespace App\Livewire\Decks;

use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Livewire\Component;

class Create extends Component
{
    public string $name = '';

    public bool $is_public;

    protected function rules(): array
    {
        return [
            'name' => [
                'required',
                'string',
                'max:20',
                Rule::unique('decks', 'name')->where('user_id', Auth::id()),
            ],
            'is_public' => [
                'required',
                'boolean',
            ],
        ];
    }

    public function store(): void
    {
        $validated = $this->validate();

        Auth::user()->decks()->create($validated);

        $this->redirect(route('decks.index', absolute: false), navigate: true);
    }
}
