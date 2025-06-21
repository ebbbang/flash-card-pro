<?php

namespace App\Policies;

use App\Models\Deck;
use App\Models\User;

class DeckPolicy
{
    public function view(User $user, Deck $deck): bool
    {
        return $user->id === $deck->user_id;
    }

    public function create(User $user): bool
    {
        return true;
    }
}
