<?php

namespace App\Http\Controllers;

use App\DnsmasqLog;
use App\Events\DhcpEvent;
use Illuminate\Http\Request;

class DnsmasqLogsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $eventJson = $request->getContent();
        $event1 = json_decode($eventJson, true);  // good assoc array
        $log = DnsmasqLog::create(['event' => $event1]);
        event(new DhcpEvent($log));

        if ($log->event['ACTION'] == 'add') {
            $message = 'New lease: ' . $log->event['IP'] . ' was leased to ' . $log->event['HOSTMAC'];
        } elseif ($log->event['ACTION'] == 'old') {
            $message = 'Old lease: ' . $log->event['IP'] . ' was previously leased to ' . $log->event['HOSTMAC'];
        } elseif ($log->event['ACTION'] == 'del') {
            $message = 'Delete lease: ' . $log->event['IP'] . ' was deleted from ' . $log->event['HOSTMAC'];
        } else {
            $message = 'Unknown DHCP action ' . $log->event['ACTION'];
        }

        app('logbot')->log($message, 'notice');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\DnsmasqLog  $dnsmasqLog
     * @return \Illuminate\Http\Response
     */
    public function show(DnsmasqLog $dnsmasqLog)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\DnsmasqLog  $dnsmasqLog
     * @return \Illuminate\Http\Response
     */
    public function edit(DnsmasqLog $dnsmasqLog)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\DnsmasqLog  $dnsmasqLog
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, DnsmasqLog $dnsmasqLog)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\DnsmasqLog  $dnsmasqLog
     * @return \Illuminate\Http\Response
     */
    public function destroy(DnsmasqLog $dnsmasqLog)
    {
        //
    }
}
