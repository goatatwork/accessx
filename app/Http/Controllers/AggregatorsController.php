<?php

namespace App\Http\Controllers;

use App\Aggregator;
use Illuminate\Http\Request;
use App\Http\Requests\AggregatorRequest;

class AggregatorsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $aggregators = Aggregator::all();

        return view('infrastructure.aggregators.index')->with('aggregators', $aggregators);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('infrastructure.aggregators.create');
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

        return redirect('/infrastructure/aggregators');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Aggregator $aggregator
     * @return \Illuminate\Http\Response
     */
    public function show(Aggregator $aggregator)
    {
        $aggregator->load('platform.module_types');
        return view('infrastructure.aggregators.show')->with('aggregator', $aggregator);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Aggregator $aggregator
     * @return \Illuminate\Http\Response
     */
    public function edit(Aggregator $aggregator)
    {
        return view('infrastructure.aggregators.edit')->with('aggregator', $aggregator);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Aggregator $aggregator
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Aggregator $aggregator)
    {
        $aggregator = tap($aggregator)->update($request->all());

        return redirect('/infrastructure/aggregators');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Aggregator $aggregator
     * @return \Illuminate\Http\Response
     */
    public function destroy(Aggregator $aggregator)
    {
        $aggregator->delete();

        return redirect('/infrastructure/aggregators');
    }
}
