<?php

namespace App\Http\Controllers;

use App\Ont;
use App\OntSoftware;
use Illuminate\Http\Request;

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
        $software = $ont->ont_software()->create($request->all());
        $software->addMediaFromRequest('uploaded_file')->toMediaCollection('default');
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
