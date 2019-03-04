<?php

namespace App\GoldAccess\Utilities;

use App\Subnet;
use App\OntProfile;
use App\OntSoftware;
use Spatie\MediaLibrary\Models\Media;
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
     * Get the path for the given model relative to the live/deployed
     * dhcp directory.
     */
    public function getDeployPath(Media $media): string
    {

        if ($media->model instanceof Subnet) {

            $origin = $media->model;

            return 'dnsmasq.d/' . $media->file_name;
        }
    }

    /*
     * Get the path for conversions of the given media, relative to the root storage path.
     */
    public function getPathForConversions(Media $media): string
    {
        return $this->getBasePath($media).'/conversions/';
    }

    /*
     * Get the path for responsive images of the given media, relative to the root storage path.
     */
    public function getPathForResponsiveImages(Media $media): string
    {
        return $this->getBasePath($media).'/responsive/';
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

        if ($media->model instanceOf Subnet) {

            $origin = $media->model;

            return $origin->dhcp_shared_network->slug . '-' . preg_replace('/\./', '_', $origin->network_address) . '_' . $origin->cidr . '/' . $media->getKey();

        }

        return $media->getKey();
    }
}
