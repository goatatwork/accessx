<?php

namespace App\GoldAccess\Dhcp;

use Storage;
use App\Subnet;
use App\DhcpSharedNetwork;
use Spatie\MediaLibrary\Models\Media;
use App\GoldAccess\Dhcp\Options\Option0;
use App\GoldAccess\Dhcp\Options\Option43;
use App\GoldAccess\Dhcp\Contracts\Deployable;
use App\GoldAccess\Utilities\MediaLibraryPathGenerator;

class Dhcpbot
{
    /**
     * @var string
     */
    public $bio = 'I am Dhcpbot';

    /**
     * Name of the disk configured in filesystems.php
     * @var string
     */
    public $dhcp_origins_disk;

    /**
     * Name of the disk configured in filesystems.php
     * @var string
     */
    public $dhcp_configs_disk;

    /**
     * Used to generate media paths
     * @var \App\GoldAccess\Utilities\MediaLibraryPathGenerator
     */
    public $medialibraray_path_generator;

    /**
     * dhcp_temp disk for writing files before adding them to media library
     * @var Storage
     */
    public $temp_storage;

    public function __construct()
    {
        $this->temp_storage = Storage::disk('dhcp_temp');
        $this->medialibraray_path_generator = new MediaLibraryPathGenerator();
        $this->dhcp_origins_disk = (env('APP_ENV') == 'testing') ? 'dhcp_origins_testing' : 'dhcp_origins';
        $this->dhcp_configs_disk = (env('APP_ENV') == 'testing') ? 'dhcp_configs_testing' : 'dhcp_configs';
    }

    /**
     * Get current DHCP state for a subnet
     * @param  Deployable $deployable
     * @return collection
     */
    public function report(Deployable $deployable)
    {
        $deployable->refresh();

        return $deployable->allMediaCollections()->map(function($collection_name, $key) use ($deployable) {
            $filename = ($deployable->getFirstMedia($collection_name)) ? $deployable->getFirstMedia($collection_name)->file_name : false;

            return [
                'collection' => $collection_name,
                'origin_exists' => $this->isBuilt($deployable, $collection_name),
                'origin_path' => $this->getOriginPath($deployable, $collection_name),
                'is_deployed' => $this->isDeployed($deployable, $collection_name),
                'deploy_path' => ($this->isDeployed($deployable, $collection_name)) ? $this->getDeployPath($deployable, $collection_name) : null,
            ];
        });
    }

    /**
     * @param  Subnet  $deployable
     * @param  string  $collection_name MediaLirbary collection name
     * @return boolean
     */
    public function isBuilt(Deployable $deployable, $collection_name)
    {
        return ($deployable->getFirstMedia($collection_name)) ? true : false;
    }

    /**
     * @param  Subnet  $deployable
     * @param  string  $collection_name MediaLirbary collection name
     * @return boolean
     */
    public function isDeployed(Deployable $deployable, $collection_name)
    {
        return Storage::disk($this->dhcp_configs_disk)->exists($this->getDeployPath($deployable, $collection_name));
    }

    /**
     * @param  Subnet  $deployable
     * @param  string  $collection_name MediaLirbary collection name
     * @return string
     */
    public function getDeployPath(Deployable $deployable, $collection_name)
    {
        if ($this->isBuilt($deployable, $collection_name)) {
            return $this->medialibraray_path_generator->getDeployPath($deployable->getFirstMedia($collection_name));
        }
    }

    /**
     * @param  Subnet  $deployable
     * @param  string  $collection_name MediaLirbary collection name
     * @return string
     */
    public function getOriginPath(Deployable $deployable, $collection_name = 'dhcp_subnet_definition')
    {
        if ($this->isBuilt($deployable, $collection_name)) {
            $path = $this->medialibraray_path_generator->getPath($deployable->getFirstMedia($collection_name));
            $filename = $deployable->getFirstMedia($collection_name)->file_name;
            return $path . $filename;
        }
    }

    /**
     * Create the MediaLibrary Media in the origins directory
     * @param  Deployable $deployable
     * @param  string $collection_name
     * @return Spatie\MediaLibrary\Models\Media
     */
    public function build(Deployable $deployable, $collection_name = 'dhcp_subnet_definition')
    {
        $media = $deployable->addMedia($this->commitFileToTempStorage($deployable, $collection_name))
            ->toMediaCollection($collection_name, $this->dhcp_origins_disk);

        return $media;
    }

    /**
     * Remove the file from the DeployPath
     * @param  Deployable $deployable
     * @param  string $collection_name
     * @return App\Subnet
     */
    public function deploy(Deployable $deployable, $collection_name = 'dhcp_subnet_definition')
    {
        if ($this->isBuilt($deployable, $collection_name)) {
            $media = $deployable->getFirstMedia($collection_name);
            $origin_path = $this->medialibraray_path_generator->getPath($media) . $media->file_name;
            $origin_file = Storage::disk($this->dhcp_origins_disk)->get($origin_path);
            $deployment = Storage::disk($this->dhcp_configs_disk)->put($this->medialibraray_path_generator->getDeployPath($media), $origin_file);
        }

        return $deployable;
    }

    /**
     * Remove the file from the DeployPath
     * @param  Deployable $deployable
     * @param  string $collection_name
     * @return App\Subnet
     */
    public function undeploy(Deployable $deployable, $collection_name = 'dhcp_subnet_definition')
    {
        if ($this->isDeployed($deployable, $collection_name)) {
            Storage::disk($this->dhcp_configs_disk)->delete($this->getDeployPath($deployable, $collection_name));
        }

        return $deployable;
    }

    /**
     * Remove the file from the DeployPath and remove the Media item.
     * Note: this means we never rid ourselves of the Media item without
     * first removing the deployed file.
     * @param  Deployable $deployable
     * @param  string $collection_name
     * @return App\Subnet
     */
    public function destroy(Deployable $deployable, $collection_name = 'dhcp_subnet_definition')
    {
        if ($this->isDeployed($deployable, $collection_name)) {
            Storage::disk($this->dhcp_configs_disk)->delete($this->getDeployPath($deployable, $collection_name));
        }

        if ($this->isBuilt($deployable, $collection_name)) {
            $deployable->clearMediaCollection($collection_name);
        }

        return $deployable;
    }

    /**
     * @param  Deployable $deployable
     * @param  string $collection_name
     * @return string|false String will be disk path to file for addMedia() to use
     */
    protected function commitFileToTempStorage(Deployable $deployable, $collection_name = 'dhcp_subnet_definition')
    {
        $dhcp_option_class = '\App\GoldAccess\Dhcp\Options\\' . $deployable->media_collections[$collection_name];

        $file_name = $dhcp_option_class::getFilename($deployable);
        $file_content = $dhcp_option_class::make($deployable);

        $path = $this->temp_storage->path($file_name);

        if ( ! $this->temp_storage->put($file_name, $file_content) ) {
            return false;
        }

        return $path;
    }
}
