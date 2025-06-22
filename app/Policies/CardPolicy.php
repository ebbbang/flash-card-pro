<?php

namespace App\Policies;

use App\Models\Card;
use App\Models\Deck;
use App\Models\User;

class CardPolicy
{
    public function create(User $user, Deck $deck): bool
    {
        return $user->id === $deck->user_id;
    }

    public function update(User $user, Card $card): bool
    {
        return $user->id === $card->deck->user_id;
    }

    public function delete(User $user, Card $card): bool
    {
        return $user->id === $card->deck->user_id;
    }
}
