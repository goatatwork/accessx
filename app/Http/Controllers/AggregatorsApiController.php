<?php

namespace App\Http\Controllers;

use App\Aggregator;
use Illuminate\Http\Request;
use App\Http\Requests\AggregatorRequest;

class AggregatorsApiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Aggregator::all();
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
     * @param  \App\Http\Requests\AggregatorRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AggregatorRequest $request)
    {
        $aggregator = $request->persist();
        $aggregator->createEmptySlots();
        return $aggregator;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Aggregator  $aggregator
     * @return \Illuminate\Http\Response
     */
    public function show(Aggregator $aggregator)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Aggregator  $aggregator
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Aggregator $aggregator)
    {
        return tap($aggregator)->update($request->all());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Aggregator  $aggregator
     * @return \Illuminate\Http\Response
     */
    public function destroy(Aggregator $aggregator)
    {
        $aggregator->delete();
    }
}
