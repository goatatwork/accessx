<?php

namespace App\Listeners;

use App\User;
use Illuminate\Auth\Events\Failed;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class LogFailedLogin
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  Failed  $event
     * @return void
     */
    public function handle(Failed $event)
    {
        $agent = new Agent();


        if ($event->user == null)
        {
            $message = 'Login failed. No such user. ' . json_encode($event->credentials) . ' with agent ' . app('agentbot')->browser();
        } else {
            $user = $this->getUser($event->user->getAuthIdentifier());
            $message = 'Login failed for user ' . $user->name . '(' . $user->id . ') with credentials ' . json_encode($event->credentials) . ' with agent ' . app('agentbot')->browser();
        }

        app('logbot')->log($message);
    }

    protected function getUser($id)
    {
        return User::find($id);
    }
}
