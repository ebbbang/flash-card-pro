<?php

namespace App\Livewire\Decks;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public function render(): Renderable
    {
        return view('livewire.decks.index', [
            'decks' => Auth::user()->decks()->latest('id')->paginate(),
        ]);
    }
}
