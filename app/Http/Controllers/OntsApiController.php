<?php

namespace App\Http\Controllers;

use App\Ont;
use Illuminate\Http\Request;
use App\Http\Requests\OntRequest;

class OntsApiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Ont::all();
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
     * @param  \App\Http\Requests\OntRequest  $request
     * @return \App\Ont
     */
    public function store(OntRequest $request)
    {
        return $request->persist();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Ont  $ont
     * @return \Illuminate\Http\Response
     */
    public function show(Ont $ont)
    {
        return $ont;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Ont  $ont
     * @return \Illuminate\Http\Response
     */
    public function edit(Ont $ont)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\OntRequest  $request
     * @param  \App\Ont  $ont
     * @return \App\Ont
     */
    public function update(OntRequest $request, Ont $ont)
    {
        return tap($ont)->update($request->all());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Ont  $ont
     * @return \Illuminate\Http\Response
     */
    public function destroy(Ont $ont)
    {
        $ont->delete();
    }
}
