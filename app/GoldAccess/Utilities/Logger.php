<?php

namespace App\GoldAccess\Utilities;

use App\ActivityLog;

class Logger
{
    /**
     * Do the thing that a logger does
     *
     * @param  string $message The message to log
     * @param string $facility One of emert, alert, crit, err, warning, notice, info, debug
     *
     * @return [type] [description]
     */
    public function log($message, $facility = 'info')
    {
        return ActivityLog::create([
            'calling_class' => debug_backtrace()[1]['class'],
            'calling_function' => debug_backtrace()[1]['function'],
            'level' => $facility,
            'message' => $message
        ]);
    }

    /**
     * The log levels as per RFC 5424
     * @return [type] [description]
     */
    protected function levels()
    {
        return [
            0 => 'emerg',
            1 => 'alert',
            2 => 'crit',
            3 => 'err',
            4 => 'warning',
            5 => 'notice',
            6 => 'info',
            7 => 'debug'
        ];
    }
}
