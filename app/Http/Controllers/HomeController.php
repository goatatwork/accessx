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
            'aggregators_count' => \App\Aggregator::count(),
            'dhcp_subnets_count' => \App\Subnet::count(),
            'onts_count' => \App\Ont::count(),
            'provisioning_records_count' => \App\ProvisioningRecord::count(),
            'activity_logs_count' => \App\ActivityLog::count(),
        ];
        return view('home')->with('stats', $stats);
    }
}
