<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $stats = [
            'customers_count' => \App\Customer::count(),
            'service_locations_count' => \App\ServiceLocation::count(),
            'provisioning_records_count' => \App\ProvisioningRecord::count(),

            'aggregators_count' => \App\Aggregator::count(),
            'slots_count' => \App\Slot::count(),
            'ports_count' => \App\Port::count(),

            'dhcp_subnets_count' => \App\Subnet::count(),
            'dhcp_ip_addresses_count' => \App\IpAddress::count(),

            'onts_count' => \App\Ont::count(),
            'activity_logs_count' => \App\ActivityLog::count(),
        ];
        return view('home')->with('stats', $stats);
    }
}
