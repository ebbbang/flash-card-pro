<?php

namespace Tests\Feature\Livewire\Settings;

use App\Livewire\Settings\ApiAccess;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class ApiAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_profile_page_is_displayed(): void
    {
        $this->actingAs(User::factory()->create());

        $this->get('/settings/api-access')->assertOk();
    }

    public function test_api_token_can_be_generated(): void
    {
        $user = User::factory()->create();

        $this->assertSame(0, $user->tokens()->where('name', config('app.name'))->count());

        Livewire::actingAs($user)
            ->test(ApiAccess::class)
            ->call('generateToken');

        $this->assertSame(1, $user->tokens()->where('name', config('app.name'))->count());
    }

    public function test_existing_api_tokens_are_deleted(): void
    {
        $user = User::factory()->create();

        Livewire::actingAs($user)
            ->test(ApiAccess::class)
            ->call('generateToken');

        $token = $user->tokens()->where('name', config('app.name'))->first();

        Livewire::actingAs($user)
            ->test(ApiAccess::class)
            ->call('generateToken');

        $this->assertDatabaseMissing('personal_access_tokens', ['id' => $token->id]);
    }
}
