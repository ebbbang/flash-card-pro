<?php

namespace Tests\Feature\Middlewares;

use App\Models\ApiLog;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LogApiUsageMiddlewareTest extends TestCase
{
    use RefreshDatabase;

    public function test_logs_api_usage(): void
    {
        $count = ApiLog::query()->count();

        $this->actingAs($user = User::factory()->create());
        $this->getJson('/api/decks');

        $this->assertSame($count + 1, ApiLog::query()->count());
        $this->assertDatabaseHas('api_logs', [
            'user_id' => $user->id,
            'url' => url('/api/decks'),
            'method' => 'GET',
            'ip' => request()->ip(),
        ]);
    }
}
