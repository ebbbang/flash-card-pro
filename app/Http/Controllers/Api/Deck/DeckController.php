<?php

namespace App\Http\Controllers\Api\Deck;

use App\Http\Controllers\Controller;
use App\Http\Resources\DeckCollection;
use App\Models\Deck;

class DeckController extends Controller
{
    public function __invoke(): DeckCollection
    {
        return new DeckCollection(Deck::withCount('cards')->public()->paginate());
    }
}
