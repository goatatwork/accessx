<?php

namespace App\Http\Controllers;

use App\Ont;
use App\Media;
use Illuminate\Http\Request;

class OntFilesApiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Ont $ont)
    {
        $media_files = $ont->getMedia();
        $the_files = $media_files->map(function($file, $key) {
            return array_merge(
                $file->getAttributes(),
                [
                    'url' => $file->getUrl(),
                    'human_readable_size' => $file->human_readable_size,
                    'description' => $file->getCustomProperty('description')
                ]
            );
        });
        return json_encode($the_files);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Ont $ont
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Ont $ont)
    {
        $file = $ont->addMediaFromRequest('uploaded_file')->withCustomProperties(['description' => $request->description])->toMediaCollection();
        return array_merge($file->toArray(), [
            'url' => $file->getUrl(),
            'human_readable_size' => $file->human_readable_size,
            'description' => $file->getCustomProperty('description')
        ]);
        // return $file->toJson();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Media  $media
     * @return \Illuminate\Http\Response
     */
    public function destroy(\App\Media $media)
    {
        $ont = Ont::find($media->model_id);
        $ont->deleteMedia($media->id);
    }
}
