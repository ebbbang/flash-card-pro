<?php

namespace App\Livewire\Settings;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class ApiAccess extends Component
{
    public string $token = '';

    public function render(): Renderable
    {
        return view('livewire.settings.api-access');
    }

    public function generateToken(): void
    {
        Auth::user()->tokens()->where('name', config('app.name'))->delete();

        $this->token = Auth::user()->createToken(config('app.name'))->plainTextToken;
    }
}
