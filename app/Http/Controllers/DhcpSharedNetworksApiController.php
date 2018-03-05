<?php

namespace App\Http\Controllers;

use App\DhcpSharedNetwork;
use Illuminate\Http\Request;
use App\Http\Requests\DhcpSharedNetworkRequest;

class DhcpSharedNetworksApiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return DhcpSharedNetwork::all();
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
     * @param  \App\Http\Requests\DhcpSharedNetworkRequest  $request
     * @return \App\DhcpSharedNetwork
     */
    public function store(DhcpSharedNetworkRequest $request)
    {
        return $request->persist();
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
     * @param  \App\DhcpSharedNetwork $dhcp_shared_network
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, DhcpSharedNetwork $dhcp_shared_network)
    {
        return tap($dhcp_shared_network)->update($request->all());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\DhcpSharedNetwork $dhcp_shared_network
     * @return \Illuminate\Http\Response
     */
    public function destroy(DhcpSharedNetwork $dhcp_shared_network)
    {
        $dhcp_shared_network->delete();
    }
}
