<?php

namespace App\Listeners;

use Log;
use Spatie\MediaLibrary\Events\MediaHasBeenAdded;

class MediaLogger
{
    public function handle(MediaHasBeenAdded $event)
    {
        $media = $event->media;
        $path = $media->getPath();
        Log::info("file {$path} has been saved for media {$media->id}");
        app('logbot')->log("File {$path} has been saved.");
    }
}
