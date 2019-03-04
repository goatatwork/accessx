<?php

namespace App\GoldAccess\Dhcp\Contracts;

interface Deployable
{
    /**
     * Assoc array of collection_name to Class name mappings for the model
     * @var [type]
     */
    // public $media_collections; // List of names of collections to set up as singleFile collections

    /**
     * Collection of collection_names from $media_collections
     * @return collection List of collections
     */
    public function allMediaCollections();

    /**
     * Laravel MediaLibrary requirement/feature for automatically registering media collections
     * @return [type] [description]
     */
    public function registerMediaCollections();
}
