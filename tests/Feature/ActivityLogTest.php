<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ActivityLogTest extends TestCase
{
    use RefreshDatabase;

    /**
     *
     * @return void
     */
    public function test_activity_log()
    {
        app('logbot')->log('I am a test log', 'crit');

        $this->assertDatabaseHas('activity_logs', [
            'calling_class' => 'App\\GoldAccess\\Utilities\\Logger',
            'calling_function' => 'log',
            'level' => 'crit',
            'message' => 'I am a test log'
        ]);
    }
}
