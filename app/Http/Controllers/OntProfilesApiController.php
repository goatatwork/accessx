<?php

namespace App\Http\Controllers;

use App\OntProfile;
use App\OntSoftware;
use Illuminate\Http\Request;

class OntProfilesApiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param \App\OntSoftware $ont_software
     * @return \Illuminate\Http\Response
     */
    public function index(OntSoftware $ont_software)
    {
        return $ont_software->ont_profiles;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param \App\OntSoftware $ont_software
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, OntSoftware $ont_software)
    {
        $profile = $ont_software->ont_profiles()->create($request->all());
        $profile->addMediaFromRequest('uploaded_file')->toMediaCollection('default');
        return $profile->load('media');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\OntProfile  $ontProfile
     * @return \Illuminate\Http\Response
     */
    public function show(OntProfile $ontProfile)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\OntProfile  $ontProfile
     * @return \Illuminate\Http\Response
     */
    public function edit(OntProfile $ontProfile)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\OntProfile  $ont_profile
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, OntProfile $ont_profile)
    {
        return tap($ont_profile)->update($request->all());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\OntProfile  $ont_profile
     * @return \Illuminate\Http\Response
     */
    public function destroy(OntProfile $ont_profile)
    {
        $ont_profile->delete();
    }
}
