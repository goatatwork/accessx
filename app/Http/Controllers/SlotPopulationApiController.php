<?php

namespace App\Http\Controllers;

use App\Slot;
use Illuminate\Http\Request;

class SlotPopulationApiController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Slot $slot
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Slot $slot)
    {
        $module_type = \App\ModuleType::find($request->module_type_id);
        return $slot->populate($module_type);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Slot  $slot
     * @return \Illuminate\Http\Response
     */
    public function destroy(Slot $slot)
    {
        return $slot->unpopulate();
    }
}
