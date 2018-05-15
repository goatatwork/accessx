<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\ActivityLog;
use App\GoldAccess\Utilities\Logger;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LogbotServiceProviderTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test LobgotServiceProvider --> app('logbot')
     * @return void
     */
    public function test_logbot_boots()
    {
        $this->assertInstanceOf(Logger::class, app('logbot'));
    }

    /**
     * Test logbot knows what levels are supported
     */
    public function test_logbot_knows_log_levels()
    {
        $levels = [
            0 => 'emerg',
            1 => 'alert',
            2 => 'crit',
            3 => 'err',
            4 => 'warning',
            5 => 'notice',
            6 => 'info',
            7 => 'debug'
        ];

        $this->assertEquals($levels, app('logbot')->getLevels());
    }

    /**
     * Test logbot can log to each level
     */
    public function test_logbot_can_log_to_each_level()
    {
        $levels = [
            0 => 'emerg',
            1 => 'alert',
            2 => 'crit',
            3 => 'err',
            4 => 'warning',
            5 => 'notice',
            6 => 'info',
            7 => 'debug'
        ];

        foreach ($levels as $level) {
            $log = factory(ActivityLog::class)->create(['level' => $level]);
            $this->assertDatabaseHas('activity_logs', ['message' => $log->message, 'level' => $level]);
        }
    }
}
