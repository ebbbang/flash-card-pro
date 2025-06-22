<?php

namespace App\Http\Controllers\Api\Card;

use App\Http\Controllers\Controller;
use App\Http\Resources\CardCollection;
use App\Models\Card;
use Illuminate\Database\Eloquent\Builder;

class CardController extends Controller
{
    public function __invoke(): CardCollection
    {
        return new CardCollection(
            Card::whereHas('deck', fn (Builder $query) => $query->public())->paginate()
        );
    }
}
