<?php

namespace App\Http\Controllers\Api\Deck;

use App\Http\Controllers\Controller;
use App\Http\Resources\CardCollection;
use App\Models\Deck;

class CardController extends Controller
{
    public function __invoke(Deck $deck): CardCollection
    {
        return new CardCollection($deck->cards()->paginate());
    }
}
