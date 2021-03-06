<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\DhcpSharedNetwork;
use App\Http\Requests\DhcpSharedNetworkRequest;

class DhcpSharedNetworksController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $dhcp_shared_networks = DhcpSharedNetwork::with('subnets')->get();
        return view('dhcp.index')->with('dhcp_shared_networks', $dhcp_shared_networks);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('dhcp.shared_networks.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\DhcpSharedNetworkRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(DhcpSharedNetworkRequest $request)
    {
        $request->persist();

        return redirect('/dhcp');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\DhcpSharedNetwork  $dhcp_shared_network
     * @return \Illuminate\Http\Response
     */
    public function show(DhcpSharedNetwork $dhcp_shared_network)
    {
        $dhcp_shared_network->load('subnets.ip_addresses');
        return view('dhcp.show')->with('dhcp_shared_network', $dhcp_shared_network);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\DhcpSharedNetwork  $dhcp_shared_network
     * @return \Illuminate\Http\Response
     */
    public function edit(DhcpSharedNetwork $dhcp_shared_network)
    {
        return view('dhcp.shared_networks.edit')->with('shared_network', $dhcp_shared_network);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\DhcpSharedNetwork  $dhcp_shared_network
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, DhcpSharedNetwork $dhcp_shared_network)
    {
        $dhcp_shared_network = tap($dhcp_shared_network)->update($request->all());

        return redirect('/dhcp');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\DhcpSharedNetwork  $dhcp_shared_network
     * @return \Illuminate\Http\Response
     */
    public function destroy(DhcpSharedNetwork $dhcp_shared_network)
    {
        $dhcp_shared_network->delete();

        return redirect('/dhcp');
    }
}
