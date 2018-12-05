<?php

namespace App\Http\Controllers;

use App\OntSoftware;
use Illuminate\Http\Request;

class OntSoftwareController extends Controller
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
     * @param  \App\OntSoftware  $ont_software
     * @return \Illuminate\Http\Response
     */
    public function show(OntSoftware $ont_software)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\OntSoftware  $ont_software
     * @return \Illuminate\Http\Response
     */
    public function edit(OntSoftware $ont_software)
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\OntSoftware  $ont_software
     * @return \Illuminate\Http\Response
     */
    public function destroy(OntSoftware $ont_software)
    {
        $ont_software->delete();

        return back();
    }
}
