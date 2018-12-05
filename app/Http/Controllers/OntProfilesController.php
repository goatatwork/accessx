<?php

namespace App\Http\Controllers;

use App\OntProfile;
use Illuminate\Http\Request;

class OntProfilesController extends Controller
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
     * @param  \App\OntProfile  $ont_profile
     * @return \Illuminate\Http\Response
     */
    public function show(OntProfile $ont_profile)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\OntProfile  $ont_profile
     * @return \Illuminate\Http\Response
     */
    public function edit(OntProfile $ont_profile)
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
        if ($request->has('rename') && $request->rename == 'true') {
            $ont_profile->file->file_name = $request->new_filename;
            $ont_profile->file->save();

            return back();
        }

        //
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

        return back();
    }
}
