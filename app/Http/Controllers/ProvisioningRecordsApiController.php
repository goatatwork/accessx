<?php

namespace App\Http\Controllers;

use App\Package;
use App\Jobs\RebootOnt;
use App\Jobs\SetRateLimit;
use App\ProvisioningRecord;
use Illuminate\Http\Request;
use App\Events\ServiceWasProvisioned;
use App\Events\DeletingProvisioningRecord;
use App\Events\ProvisioningRecordWasUpdated;
use App\Http\Requests\ProvisioningRecordRequest;
use App\Http\Resources\ProvisioningRecordForEditingResource;

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

        event (new ServiceWasProvisioned($provisioning_record, $request->package_id));

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
        return new ProvisioningRecordForEditingResource($provisioning_record);
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
        // package_change is only on the package selection form on change-page
        if ($request->package_change) {
            SetRateLimit::dispatch($request->package_id, $provisioning_record);
            return ['success' => true];
        }

        app('dhcpbot')->destroy($provisioning_record, 'dhcp_management_ip');

        $provisioning_record = $request->persistUpdate($provisioning_record);

        event (new ProvisioningRecordWasUpdated($provisioning_record));

        if ($request->reboot) {
            RebootOnt::dispatch($provisioning_record);
        }

        return new ProvisioningRecordForEditingResource($provisioning_record);
        // return $provisioning_record;
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
