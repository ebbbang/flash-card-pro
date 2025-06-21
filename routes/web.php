<?php

use App\Livewire\Cards;
use App\Livewire\Decks;
use App\Livewire\Settings\Appearance;
use App\Livewire\Settings\Password;
use App\Livewire\Settings\Profile;
use App\Models\Deck;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Route::get('settings/profile', Profile::class)->name('settings.profile');
    Route::get('settings/password', Password::class)->name('settings.password');
    Route::get('settings/appearance', Appearance::class)->name('settings.appearance');

    // Decks
    Route::get('/decks', Decks\Index::class)->name('decks.index');
    Route::get('/decks/create', Decks\Create::class)
        ->can('create', Deck::class)
        ->name('decks.create');
    Route::get('/decks/{deck}', Decks\Show::class)
        ->can('view', 'deck')
        ->name('decks.show');

    // Cards
    Route::prefix('decks/{deck}')
        ->can('view', 'deck')
        ->group(function () {
            Route::get('/cards/create', Cards\Create::class)
//                ->can('create', Card::class)
                ->name('cards.create');
        });
});

require __DIR__.'/auth.php';
