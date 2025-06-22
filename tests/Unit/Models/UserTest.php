<?php

namespace Tests\Unit\Models;

use App\Models\ApiLog;
use App\Models\Deck;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Tests\TestCase;

class UserTest extends TestCase
{
    public function test_model_extends_base_user()
    {
        $extends = get_parent_class(User::class);

        $this->assertSame(\Illuminate\Foundation\Auth\User::class, $extends);
    }

    public function test_model_has_traits()
    {
        $traits = class_uses(User::class);

        $this->assertSame(
            [HasApiTokens::class, HasFactory::class, Notifiable::class],
            array_keys($traits)
        );
    }

    public function test_model_has_fillable_properties()
    {
        $fillable = (new User)->getFillable();

        $this->assertSame(
            [
                'name',
                'email',
                'password',
            ],
            $fillable
        );
    }

    public function test_model_has_hidden_properties()
    {
        $hidden = (new User)->getHidden();

        $this->assertSame(
            [
                'password',
                'remember_token',
            ],
            $hidden
        );
    }

    public function test_model_has_casts()
    {
        $casts = (new User)->getCasts();

        $this->assertSame(
            [
                'id' => 'int',
                'email_verified_at' => 'datetime',
                'password' => 'hashed',
            ],
            $casts
        );
    }

    public function test_model_has_initials_method()
    {
        $user = new User([
            'name' => 'John Doe',
        ]);

        $this->assertSame('JD', $user->initials());
    }

    public function test_model_has_many_decks()
    {
        $user = new User;

        $this->assertInstanceOf(HasMany::class, $user->decks());
        $this->assertInstanceOf(Deck::class, $user->decks()->getModel());
    }

    public function test_model_has_many_api_logs()
    {
        $user = new User;

        $this->assertInstanceOf(HasMany::class, $user->apiLogs());
        $this->assertInstanceOf(ApiLog::class, $user->apiLogs()->getModel());
    }
}
