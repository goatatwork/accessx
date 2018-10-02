<?php

namespace App\Http\Controllers;

use File;
use App\OntProfile;
use App\OntSoftware;
use Illuminate\Http\Request;
use App\GoldAccess\Ont\ZhoneFilenameConverter;
use App\GoldAccess\Ont\ZhoneConfigFilenameGenerator;
use App\Rules\OneSuspendedProfilePerSoftwareVersion;

class OntProfilesApiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param \App\OntSoftware $ont_software
     * @return \Illuminate\Http\Response
     */
    public function index(OntSoftware $ont_software)
    {
        return $ont_software->ont_profiles;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param \App\OntSoftware $ont_software
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, OntSoftware $ont_software)
    {
        $validatedData = $request->validate([
            'name' => [
                'required',
                'max:255',
                new OneSuspendedProfilePerSoftwareVersion($ont_software)
            ]
        ]);

        if ($ont_software->ont->manufacturer == 'Zhone') {

            $config_filename_generator = new ZhoneConfigFilenameGenerator($ont_software);
            $configFileName = $config_filename_generator->generate();

            $profile = $ont_software->ont_profiles()->create($request->all());
            $profile->addMediaFromRequest('uploaded_file')
                ->usingFileName($configFileName)
                ->toMediaCollection('default');

            $this->copySoftwareImageToProfileDirectory($profile, $ont_software);

            return $profile->load('media');
        } else {
            // S0301243_0GF_generic.conf
            $profile = $ont_software->ont_profiles()->create($request->all());
            $profile->addMediaFromRequest('uploaded_file')->toMediaCollection('default');
            return $profile->load('media');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\OntProfile  $ontProfile
     * @return \Illuminate\Http\Response
     */
    public function show(OntProfile $ontProfile)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\OntProfile  $ontProfile
     * @return \Illuminate\Http\Response
     */
    public function edit(OntProfile $ontProfile)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\OntProfile  $ont_profile
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, OntProfile $ont_profile)
    {
        return tap($ont_profile)->update($request->all());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\OntProfile  $ont_profile
     * @return \Illuminate\Http\Response
     */
    public function destroy(OntProfile $ont_profile)
    {
        $ont_profile->delete();
    }

    public function copySoftwareImageToProfileDirectory($profile, $ont_software)
    {
        $ont_software->getFirstMedia();
        $source = $ont_software->file->getPath();

        $profile_path = $profile->file->getPath();
        $profile_path_parts = explode('/',$profile_path);
        array_pop($profile_path_parts);
        $profile_path_usable = implode('/',$profile_path_parts);

        $destination = $profile_path_usable . '/' . $ont_software->file->file_name;

        File::copy($source, $destination);
    }
}
