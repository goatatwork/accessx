<?php

namespace App\GoldAccess\Utilities;

use App\OntProfile;
use App\OntSoftware;
use Spatie\MediaLibrary\Media;
use Spatie\MediaLibrary\PathGenerator\PathGenerator;

class MediaLibraryPathGenerator implements PathGenerator
{
    /*
     * Get the path for the given media, relative to the root storage path.
     */
    public function getPath(Media $media): string
    {
        return $this->getBasePath($media).'/';
    }

    /*
     * Get the path for conversions of the given media, relative to the root storage path.
     */
    public function getPathForConversions(Media $media): string
    {
        return $this->getBasePath($media).'/conversions/';
    }

    /*
     * Get a (unique) base path for the given media.
     */
    protected function getBasePath(Media $media): string
    {

        if ($media->model instanceof OntProfile) {

            $origin = $media->model;

            if ($origin->ont_software->ont->manufacturer == 'Zhone') {
                return 'ont_profiles/' . $origin->ont_software->ont->slug . '/' . $origin->ont_software->version . '/' . $origin->slug;
            }

        }

        if ($media->model instanceof OntSoftware) {

            $origin = $media->model;

            if ($origin->ont->manufacturer == 'Zhone') {
                return 'ont_profiles/' . $origin->ont->slug . '/' . $origin->version . '/SoftwareImage';
            }

        }

        return $media->getKey();
    }
}
