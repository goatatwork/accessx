<?php

namespace App\Http\Controllers;

use App\GaSetting;
use Illuminate\Http\Request;
use App\Http\Requests\GaSettingRequest;

class GaSettingsApiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return GaSetting::all();
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
     * @param  \App\Http\Requests\GaSettingRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(GaSettingRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  string  $setting
     * @return \Illuminate\Http\Response
     */
    public function show($setting)
    {
        return GaSetting::where('name', $setting)->first() ?? abort(404);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\GaSetting  $gaSetting
     * @return \Illuminate\Http\Response
     */
    public function edit(GaSetting $gaSetting)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\GaSettingRequest  $request
     * @param  string  $setting
     * @return \Illuminate\Http\Response
     */
    public function update(GaSettingRequest $request, $setting)
    {
        $setting = GaSetting::where('name', $setting)->first() ?? abort(404);

        return tap($setting)->update($request->all());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\GaSetting  $gaSetting
     * @return \Illuminate\Http\Response
     */
    public function destroy(GaSetting $gaSetting)
    {
        //
    }
}
