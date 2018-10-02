<?php

namespace App\Http\Controllers;

use App\Ont;
use App\OntSoftware;
use Illuminate\Http\Request;
use App\GoldAccess\Ont\ZhoneFilenameConverter;

class OntSoftwareApiController extends Controller
{
    /**
     * Display a listing of the resource.
     * @param \App\Ont $ont
     * @return \App\OntSoftware collection
     */
    public function index(Ont $ont)
    {
        return $ont->ont_software;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Ont $ont
     * @return \App\OntSoftware
     */
    public function store(Request $request, Ont $ont)
    {



        if ($ont->manufacturer == 'Zhone') {  // ZNID-24xxA-301266-SIP.img is how these start

            $converter = new ZhoneFilenameConverter($request->uploaded_file->getClientOriginalName(), $ont->oem, $ont->model_number);
            $converter->calculate();

            $software = $ont->ont_software()->create(['version' => $converter->version_string_for_database, 'notes' => $request->notes]);

            $software->addMediaFromRequest('uploaded_file')
                ->usingFileName($converter->getDestinationFilename())
                ->withCustomProperties(['dhcp_string' => $converter->dhcp_config_string])
                ->toMediaCollection('default');

            // $new_filename = ''; // ZNID24xxA_GRSIP_0301266_image_with_cfe.img (GF models) or ZNID24xxASIP_0301266_image_with_cfe.img (Zhone models)
            // $dhcp_config_string = ''; // S301266
            // $original_filename = explode('.', $request->uploaded_file->getClientOriginalName())[0];  // ZNID-24xxA-301266-SIP
            // $parts = explode('-', $original_filename);
            // $short_voip = ($parts[3] == 'SIP') ? 'S' : 'M';
            // $dhcp_config_string = $short_voip . '0' . $parts[2];

            // $new_filename = $parts[0] . $parts[1] . '_GR' . $parts[3] . '_0' . $parts[2] . '_image_with_cfe.img';

            // // S03.01.266
            // $characters = str_split($parts[2]);
            // $converted_version = $short_voip . '0' . $characters[0] . '.' . $characters[1] . $characters[2] . '.' . $characters[3] . $characters[4] . $characters[5];

            // $software = $ont->ont_software()->create(['version' => $converted_version, 'notes' => $request->notes]);

            // $software->addMediaFromRequest('uploaded_file')
            //     ->usingFileName($new_filename)
            //     ->withCustomProperties(['dhcp_string' => $dhcp_config_string])
            //     ->toMediaCollection('default');
        } else {
            $software = $ont->ont_software()->create($request->all());
            $software->addMediaFromRequest('uploaded_file')->toMediaCollection('default');
        }


        return $software;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\OntSoftware  $ontSoftware
     * @return \Illuminate\Http\Response
     */
    public function show(OntSoftware $ontSoftware)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\OntSoftware  $ontSoftware
     * @return \Illuminate\Http\Response
     */
    public function edit(OntSoftware $ontSoftware)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\OntSoftware  $ont_software
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, OntSoftware $ont_software)
    {
        return tap($ont_software)->update($request->all());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\OntSoftware  $ontSoftware
     * @return \Illuminate\Http\Response
     */
    public function destroy(OntSoftware $ont_software)
    {
        $ont_software->ont_profiles->map(function($profile, $key) {
            $profile->clearMediaCollection('default');
        });
        $ont_software->delete();
    }
}
