<?php

namespace Tests\Unit\Models;

use App\Models\Deck;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Tests\TestCase;

class DeckTest extends TestCase
{
    public function test_model_extends_base_user()
    {
        $extends = get_parent_class(Deck::class);

        $this->assertSame(Model::class, $extends);
    }

    public function test_model_has_traits()
    {
        $traits = class_uses(Deck::class);

        $this->assertSame(
            [HasFactory::class],
            array_keys($traits)
        );
    }

    public function test_model_has_fillable_properties()
    {
        $fillable = (new Deck)->getFillable();

        $this->assertSame(['name'], $fillable);
    }

    public function test_model_belongs_to_user()
    {
        $user = new Deck;

        $this->assertInstanceOf(BelongsTo::class, $user->user());
        $this->assertInstanceOf(User::class, $user->user()->getModel());
    }
}
