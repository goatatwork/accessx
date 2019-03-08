<?php

namespace App\Http\Controllers;

use Storage;
use Illuminate\Http\Request;

class DhcpLeasesFileController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $lease_file = Storage::disk('dhcp_configs')->get('leases/dnsmasq.leases');

        $leases = collect(preg_split('/\n/', $lease_file));

        $leases = $leases->filter(function($lease) {
            return $lease;
        })->map(function($lease) {
            $lease_parts = preg_split('/\s+/', $lease);

            if (sizeof($lease_parts) == 5) {
                return [
                    'time' => $lease_parts[0],
                    'mac' => $lease_parts[1],
                    'ip' => $lease_parts[2],
                    'hostname' => $lease_parts[3],
                    'client_id' => $lease_parts[4],
                ];
            }
        });

        return view('dhcp.leases')->with('leases', $leases);
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
