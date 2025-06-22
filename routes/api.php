<?php

use App\Http\Controllers\Api\Card as ApiCard;
use App\Http\Controllers\Api\Deck as ApiDeck;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')
    ->group(function () {
        Route::get('/user', function (Request $request) {
            return $request->user();
        });

        Route::get('/decks', ApiDeck\DeckController::class);
        Route::get('/decks/{deck}/cards', ApiDeck\CardController::class)
            ->can('view', 'deck');

        Route::get('/cards', ApiCard\CardController::class);
    });
