<?php

namespace App\Http\Controllers;

use App\Platform;
use App\ModuleType;
use Illuminate\Http\Request;
use App\Http\Requests\ModuleTypeRequest;

class ModuleTypesApiController extends Controller
{
    /**
     * Display a listing of the resource.
     * @param  \App\Platform $platform
     * @return \Illuminate\Http\Response
     */
    public function index(Platform $platform)
    {
        return $platform->module_types;
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
     * @param  \App\Http\Requests\ModuleTypeRequest  $request
     * @param \App\Platform $platform
     * @return \Illuminate\Http\Response
     */
    public function store(ModuleTypeRequest $request, Platform $platform)
    {
        return $request->persist($platform);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\ModuleType  $module_type
     * @return \Illuminate\Http\Response
     */
    public function show(ModuleType $module_type)
    {
        return $module_type;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\ModuleTypeRequest  $request
     * @param  \App\ModuleType  $moduleType
     * @return \Illuminate\Http\Response
     */
    public function update(ModuleTypeRequest $request, ModuleType $module_type)
    {
        return tap($module_type)->update($request->all());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\ModuleType  $moduleType
     * @return \Illuminate\Http\Response
     */
    public function destroy(ModuleType $module_type)
    {
        $module_type->delete();
    }
}
