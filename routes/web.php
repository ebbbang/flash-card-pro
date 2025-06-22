<?php

use App\Livewire\Cards;
use App\Livewire\Decks;
use App\Livewire\Settings\Appearance;
use App\Livewire\Settings\Password;
use App\Livewire\Settings\Profile;
use App\Livewire\Studies;
use App\Models\Card;
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

    Route::prefix('decks/{deck}')
        ->group(function () {
            // Cards
            Route::get('/cards', Cards\Index::class)
                ->can('view', 'deck')
                ->name('cards.index');
            Route::get('/cards/create', Cards\Create::class)
                ->can('view', 'deck')
                ->can('create', Card::class)
                ->name('cards.create');
            Route::get('/cards/{card}/edit', Cards\Edit::class)
                ->can('view', 'deck')
                ->can('update', 'card')
                ->name('cards.edit');

            // Studies
            Route::get('/studies', Studies\Index::class)
                ->can('view', 'deck')
                ->name('studies.index');
        });
});

require __DIR__.'/auth.php';
