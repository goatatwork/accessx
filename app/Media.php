<?php

namespace App;

use OwenIt\Auditing\Auditable;
use Spatie\MediaLibrary\Media as BaseMedia;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;

class Media extends BaseMedia implements AuditableContract
{
    use Auditable;

    protected $appends = ['url', 'human_readable_size'];

    public function getUrlAttribute()
    {
        return $this->getUrl();
    }
}
