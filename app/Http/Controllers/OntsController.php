<?php

namespace App\Http\Controllers;

use App\Ont;
use Illuminate\Http\Request;
use App\Http\Requests\OntRequest;

class OntsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('onts.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('onts.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\OntRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(OntRequest $request)
    {
        $ont = $request->persist();

        return redirect('/onts');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Ont  $ont
     * @return \App\Ont with data from ->getMedia()
     */
    public function show(Ont $ont)
    {
        $ont->getMedia();
        return view('onts.show')->with('ont', $ont);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Ont $ont
     * @return \Illuminate\Http\Response
     */
    public function edit(Ont $ont)
    {
        return view('onts.edit')->with('ont', $ont);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\OntRequest  $request
     * @param  \App\Ont $ont
     * @return \Illuminate\Http\Response
     */
    public function update(OntRequest $request, Ont $ont)
    {
        $ont = tap($ont)->update($request->all());

        return redirect('/onts/'.$ont->id)->with('ont', $ont);
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

        return redirect('/onts');
    }
}
