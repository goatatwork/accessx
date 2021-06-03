<?php

namespace App\Http\Controllers;

use App\Package;
use App\ProvisioningRecord;
use Illuminate\Http\Request;
use App\Jobs\DisablePort;
use App\Jobs\EnablePort;
use App\Jobs\RebootOnt;
use App\Http\Requests\ProvisioningRecordRequest;

class ProvisioningRecordController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $provisioning_records = ProvisioningRecord::with([
            'service_location.customer.billing_record',
            'ont_profile.ont_software.ont',
            'port.slot.aggregator',
            'ip_address.subnet.dhcp_shared_network'
        ])->get();

        return view('provisioning.index')->with('provisioning_records', $provisioning_records);
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
     * @param  \App\Http\Requests\ProvisioningRecordRequest  $request
     * @param  \App\ProvisioningRecord  $provisioning_record
     * @return \Illuminate\Http\Response
     */
    public function show(ProvisioningRecordRequest $request, ProvisioningRecord $provisioning_record)
    {
        // $management_ip = new ManagementIp($provisioning_record);

        $other_possible_packages_json = json_encode($provisioning_record->ont_profile->ont_software->ont_profiles->pluck('name', 'id'));

        return view('provisioning.show')
            // ->with('management_ip', $management_ip)
            ->with('other_possible_packages_json', $other_possible_packages_json)
            ->with('provisioning_record', $provisioning_record);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\ProvisioningRecord  $provisioning_record
     * @return \Illuminate\Http\Response
     */
    public function edit(ProvisioningRecord $provisioning_record)
    {
        $service_location = $provisioning_record->service_location;

        $speed_packages = Package::all();

        return view('provisioning.edit')
            ->with('provisioning_record', $provisioning_record)
            ->with('service_location', $service_location)
            ->with('speed_packages', $speed_packages);
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\ProvisioningRecord  $provisioning_record
     * @return \Illuminate\Http\Response
     */
    public function destroy(ProvisioningRecord $provisioning_record)
    {
        $provisioning_record->delete();

        return redirect('/provisioning');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\ProvisioningRecordRequest  $request
     * @param  \App\ProvisioningRecord  $provisioning_record
     * @return \Illuminate\Http\Response
     */
    public function suspend(ProvisioningRecordRequest $request, ProvisioningRecord $provisioning_record)
    {
        $notes = $request->notes ? $request->notes : '';

        $provisioning_record = tap($provisioning_record)->update([
            'notes' => $notes . ' ::: ' . $provisioning_record->notes,
            'suspended' => true
        ]);

        $pr = ProvisioningRecord::find($provisioning_record->id);
        DisablePort::dispatch($pr);

        return redirect('/provisioning/' . $provisioning_record->id)->with('status', 'suspended');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\ProvisioningRecordRequest  $request
     * @param  \App\ProvisioningRecord  $provisioning_record
     * @return \Illuminate\Http\Response
     */
    public function unsuspend(ProvisioningRecordRequest $request, ProvisioningRecord $provisioning_record)
    {
        $notes = $request->notes ? $request->notes : '';

        $provisioning_record = tap($provisioning_record)->update([
            'notes' => $notes . ' ::: ' . $provisioning_record->notes,
            'suspended' => false
        ]);

        $pr = ProvisioningRecord::find($provisioning_record->id);
        EnablePort::dispatch($pr);

        return redirect('/provisioning/' . $provisioning_record->id)->with('status', 'unsuspended');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\ProvisioningRecordRequest  $request
     * @param  \App\ProvisioningRecord  $provisioning_record
     * @return \Illuminate\Http\Response
     */
    public function factoryReset(ProvisioningRecordRequest $request, ProvisioningRecord $provisioning_record)
    {
        RebootOnt::dispatch($provisioning_record);

        return redirect('/provisioning/' . $provisioning_record->id)->with('status', 'factory');
    }
}
