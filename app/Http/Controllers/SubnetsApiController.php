<?php

namespace App\Http\Controllers;

use App\Subnet;
use App\DhcpSharedNetwork;
use Illuminate\Http\Request;
use App\Events\SubnetWasCreated;
use App\Http\Requests\SubnetRequest;

class SubnetsApiController extends Controller
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
     * @param  \App\Http\Requests\SubnetRequest  $request
     * @return \App\Subnet
     */
    public function store(SubnetRequest $request, DhcpSharedNetwork $dhcp_shared_network)
    {
        $subnet = $dhcp_shared_network->subnets()->create($request->all());
        event(new SubnetWasCreated($subnet));
        return $subnet;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Subnet  $subnet
     * @return \Illuminate\Http\Response
     */
    public function show(Subnet $subnet)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Subnet  $subnet
     * @return \Illuminate\Http\Response
     */
    public function edit(Subnet $subnet)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Subnet  $subnet
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Subnet $subnet)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Subnet  $subnet
     * @return \Illuminate\Http\Response
     */
    public function destroy(Subnet $subnet)
    {
        //
    }
}
