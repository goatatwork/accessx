<?php

namespace App\Http\Controllers;

use App\ProvisioningRecord;
use Illuminate\Http\Request;
use App\GoldAccess\Dhcp\ManagementIp;
use App\Http\Resources\ProvisioningRecordForTable;

class ProvisioningRecordController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $provisioning_records = ProvisioningRecordForTable::collection(ProvisioningRecord::all());

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
     * @param  \App\ProvisioningRecord  $provisioning_record
     * @return \Illuminate\Http\Response
     */
    public function show(ProvisioningRecord $provisioning_record)
    {
        $management_ip = new ManagementIp($provisioning_record);

        $other_possible_packages_json = json_encode($provisioning_record->ont_profile->ont_software->ont_profiles->pluck('name', 'id'));

        return view('provisioning.show')
            ->with('management_ip', $management_ip)
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

        return view('provisioning.edit')->with('provisioning_record', $provisioning_record)->with('service_location', $service_location);
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
}
