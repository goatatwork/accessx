<?php

namespace App\Listeners;

use Log;
use App\Subnet;
use Spatie\MediaLibrary\Events\MediaHasBeenAdded;

class MediaLogger
{
    public function handle(MediaHasBeenAdded $event)
    {
        $media = $event->media;

        $path = $media->getPath();

        app('logbot')->log("File {$path} has been saved for media id {$media->id}");
    }
}
