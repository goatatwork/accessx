<?php

namespace App\Http\Controllers;

// use App\GaSetting;
use App\Package;
use Illuminate\Http\Request;

class OfferingsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $thePackages = Package::all();
        return view('settings.offerings.index')->with('offerings', $thePackages);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    // public function create()
    // {
    //     //
    // }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    // public function store(Request $request)
    // {
    //     //
    // }

    /**
     * Display the specified resource.
     *
     * @param  \App\GaSetting  $setting
     * @return \Illuminate\Http\Response
     */
    // public function show(GaSetting $setting)
    // {
    //     //
    // }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\GaSetting  $gaSetting
     * @return \Illuminate\Http\Response
     */
    // public function edit(GaSetting $gaSetting)
    // {
    //     //
    // }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\GaSetting  $gaSetting
     * @return \Illuminate\Http\Response
     */
    // public function update(Request $request, GaSetting $gaSetting)
    // {
    //     //
    // }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\GaSetting  $gaSetting
     * @return \Illuminate\Http\Response
     */
    // public function destroy(GaSetting $gaSetting)
    // {
    //     //
    // }
}
