<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\ActivityLog;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ActivityLogTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function test_the_activity_log_factory_works()
    {
        $log = factory(ActivityLog::class)->create();

        $this->assertDatabaseHas('activity_logs', [
            'calling_class' => $log->calling_class,
            'calling_function' => $log->calling_function,
            'level' => $log->level,
            'message' => $log->message
        ]);
    }

    /**
     *
     * @return void
     */
    public function test_logbot_has_a_log_method_that_adds_an_entry_to_the_activity_logs_table_in_the_database()
    {
        app('logbot')->log('I am a test log', 'crit');

        $this->assertDatabaseHas('activity_logs', [
            'level' => 'crit',
            'message' => 'I am a test log'
        ]);
    }
}
