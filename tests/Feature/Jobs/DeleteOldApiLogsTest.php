<?php

namespace Feature\Jobs;

use App\Jobs\DeleteOldApiLogs;
use App\Models\ApiLog;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DeleteOldApiLogsTest extends TestCase
{
    use RefreshDatabase;

    public function test_deletes_old_api_logs(): void
    {
        $expiredLogs = ApiLog::factory()->count(mt_rand(2, 5))->create(['created_at' => now()->subDays(31)]);
        $activeLogs = ApiLog::factory()->count(mt_rand(2, 5))->create(['created_at' => now()->subDays(29)]);

        $count = ApiLog::query()->count();

        DeleteOldApiLogs::dispatchSync();

        $this->assertSame($count - $expiredLogs->count(), ApiLog::query()->count());
        $this->assertDatabaseMissing('api_logs', ['id' => $expiredLogs[0]->id]);
        $this->assertDatabaseHas('api_logs', ['id' => $activeLogs[0]->id]);
    }
}
