<?php

namespace App\Http\Controllers;

use App\Customer;
use App\ServiceLocation;
use Illuminate\Http\Request;
use App\Http\Requests\ServiceLocationRequest;

class ServiceLocationsApiController extends Controller
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
     * @param  \App\Http\Requests\ServiceLocationRequest $request
     * @return \App\ServiceLocation
     */
    public function store(ServiceLocationRequest $request, Customer $customer)
    {
        return $request->persist();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\ServiceLocation  $serviceLocation
     * @return \Illuminate\Http\Response
     */
    public function show(ServiceLocation $serviceLocation)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\ServiceLocation  $serviceLocation
     * @return \Illuminate\Http\Response
     */
    public function edit(ServiceLocation $serviceLocation)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\ServiceLocation  $serviceLocation
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ServiceLocation $service_location)
    {
        return tap($service_location)->update($request->all());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\ServiceLocation  $serviceLocation
     * @return \Illuminate\Http\Response
     */
    public function destroy(ServiceLocation $service_location)
    {
        $service_location->delete();
    }
}
