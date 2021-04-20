<?php

namespace App\Http\Controllers;

use App\Jobs\SetRateLimit;
use App\Package;
use App\ProvisioningRecord;
use Illuminate\Http\Request;

class ProvisioningRecordPackageController extends Controller
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\ProvisioningRecord  $provisioningRecord
     * @return \Illuminate\Http\Response
     */
    public function show(ProvisioningRecord $provisioningRecord)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\ProvisioningRecord  $provisioningRecord
     * @return \Illuminate\Http\Response
     */
    public function edit(ProvisioningRecord $provisioningRecord)
    {
        return view('provisioning.change-package')
            ->with('provisioning_record', $provisioningRecord);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\ProvisioningRecord  $provisioningRecord
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ProvisioningRecord $provisioningRecord)
    {
        $package = Package::find($request->package);
        $provisioningRecord->packages()->save($package);

        SetRateLimit::dispatch($package->id, $provisioningRecord);

        return redirect()->route('provisioning-show',['provisioning_record'=>$provisioningRecord->id]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\ProvisioningRecord  $provisioningRecord
     * @return \Illuminate\Http\Response
     */
    public function destroy(ProvisioningRecord $provisioningRecord)
    {
        //
    }
}
