<?php

namespace App\Http\Controllers;

use App\ProvisioningRecord;
use Illuminate\Http\Request;
use App\Events\ServiceWasProvisioned;
use App\Events\DeletingProvisioningRecord;
use App\Events\ProvisioningRecordWasUpdated;
use App\Http\Requests\ProvisioningRecordRequest;

class ProvisioningRecordsApiController extends Controller
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
     * @param  \App\Http\Requests\ProvisioningRecordRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProvisioningRecordRequest $request)
    {
        $provisioning_record = $request->persist();

        event (new ServiceWasProvisioned($provisioning_record));

        return $provisioning_record;
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
     * @param  \App\ProvisioningRecord  $provisioning_record
     * @return \Illuminate\Http\Response
     */
    public function edit(ProvisioningRecord $provisioning_record)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\ProvisioningRecordRequest  $request
     * @param  \App\ProvisioningRecord  $provisioning_record
     * @return \Illuminate\Http\Response
     */
    public function update(ProvisioningRecordRequest $request, ProvisioningRecord $provisioning_record)
    {
        $provisioning_record = tap($provisioning_record)->update($request->all());

        event (new ProvisioningRecordWasUpdated($provisioning_record));

        return $provisioning_record;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\ProvisioningRecord  $provisioning_record
     * @return \Illuminate\Http\Response
     */
    public function destroy(ProvisioningRecord $provisioning_record)
    {
        event (new DeletingProvisioningRecord($provisioning_record));

        $provisioning_record->delete();
    }
}
