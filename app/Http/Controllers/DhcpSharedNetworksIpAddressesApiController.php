<?php

namespace App\Http\Controllers;

use App\DhcpSharedNetwork;
use Illuminate\Http\Request;

class DhcpSharedNetworksIpAddressesApiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  \App\DhcpSharedNetwork $dhcp_shared_network [<description>]
     * @return \Illuminate\Http\Response
     */
    public function index(DhcpSharedNetwork $dhcp_shared_network)
    {
        return $dhcp_shared_network->ip_addresses->map(function($ip) {
            return [
                'id' => $ip->id,
                'address' => $ip->address,
                'vlan' => $ip->vlan,
                'exclude_from_dhcp' => $ip->exclude_from_dhcp,
                'has_provisioning_records' => $ip->has_provisioning_records
            ];
        });
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
