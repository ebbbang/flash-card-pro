<?php

namespace Tests\Unit\Models;

use App\Models\Card;
use App\Models\Deck;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Tests\TestCase;

class CardTest extends TestCase
{
    public function test_model_extends_base_user()
    {
        $extends = get_parent_class(Card::class);

        $this->assertSame(Model::class, $extends);
    }

    public function test_model_has_traits()
    {
        $traits = class_uses(Card::class);

        $this->assertSame(
            [HasFactory::class],
            array_keys($traits)
        );
    }

    public function test_model_has_fillable_properties()
    {
        $fillable = (new Card)->getFillable();

        $this->assertSame(['question', 'answer'], $fillable);
    }

    public function test_model_belongs_to_deck()
    {
        $card = new Card;

        $this->assertInstanceOf(BelongsTo::class, $card->deck());
        $this->assertInstanceOf(Deck::class, $card->deck()->getModel());
    }
}
