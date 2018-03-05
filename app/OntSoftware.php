<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\MediaLibrary\HasMedia\Interfaces\HasMedia;

class OntSoftware extends Model implements HasMedia
{
    use HasMediaTrait;

    protected $fillable = ['version','notes'];

    protected $table = 'ont_software';

    protected $appends = ['file'];

    public function ont()
    {
        return $this->belongsTo(Ont::class);
    }

    public function ont_profiles()
    {
        return $this->HasMany(OntProfile::class);
    }

    public function getFileAttribute()
    {
        return $this->getFirstMedia();
    }
}
