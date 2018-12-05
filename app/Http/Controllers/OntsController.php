<?php

namespace App\Http\Controllers;

use App\Ont;
use App\OntSoftware;
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
     * @param  \Illuminate\Http\Request $request
     * @return \App\Ont with data from ->getMedia()
     */
    public function show(Ont $ont, Request $request)
    {
        // $the_files = $ont->getMedia()->map(function($file, $key) {
        //     return array_merge(
        //         $file->getAttributes(),
        //         [
        //             'url' => $file->getUrl(),
        //             'human_readable_size' => $file->human_readable_size,
        //             'description' => $file->getCustomProperty('description')
        //         ]
        //     );
        // });

        // return view('onts.show')->with('ont', $ont)->with('media_files', $the_files);

        if ($request->has('viewsoftware')) {
            if ($ont->ont_software()->pluck('id')->contains($request->viewsoftware)) {
                $view_software = OntSoftware::find($request->viewsoftware);
            } else {
                $view_software = null;
            }
        } else {
            $view_software = null;
        }

        $the_files = $ont->getMedia()->map(function($file, $key) {
            return array_merge(
                $file->getAttributes(),
                [
                    'url' => $file->getUrl(),
                    'human_readable_size' => $file->human_readable_size,
                    'description' => $file->getCustomProperty('description')
                ]
            );
        });

        return view('onts.show1')->with('ont', $ont)->with('media_files', $the_files)->with('view_software', $view_software);
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
        $update_data = $request->all();

        if (!$request->has('indoor')) {
            data_fill($update_data, 'indoor', false);
        }
        if (!$request->has('wifi')) {
            data_fill($update_data, 'wifi', false);
        }
        if (!$request->has('oem')) {
            data_fill($update_data, 'oem', false);
        }

        $ont = tap($ont)->update($update_data);

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
