<?php

namespace App\Exceptions;

use Exception;

class SlotAlreadyHasPorts extends Exception
{
    /**
     * Render the exception as an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function render($request)
    {
        \Log::info('Slot already has ports.');
    }
}
