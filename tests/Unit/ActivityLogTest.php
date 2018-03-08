<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\ActivityLog;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ActivityLogTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_a_log_entry_can_exist()
    {
        $log = factory(ActivityLog::class)->create();

        $this->assertDatabaseHas('activity_logs', ['calling_class' => $log->calling_class]);
    }
}
