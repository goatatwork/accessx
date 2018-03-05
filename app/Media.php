<?php

namespace App;

use Spatie\MediaLibrary\Media as BaseMedia;

class Media extends BaseMedia
{
    protected $appends = ['url', 'human_readable_size'];

    public function getUrlAttribute()
    {
        return $this->getUrl();
    }
}
