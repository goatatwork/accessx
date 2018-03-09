<?php

namespace App;

use Spatie\Sluggable\HasSlug;
use OwenIt\Auditing\Auditable;
use Spatie\Sluggable\SlugOptions;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\MediaLibrary\HasMedia\Interfaces\HasMedia;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;

class OntProfile extends Model implements HasMedia, AuditableContract
{
    use HasMediaTrait, Provisionable, HasSlug, Auditable;

    protected $fillable = ['name', 'notes'];

    protected $appends = ['file'];

    public function ont_software()
    {
        return $this->belongsTo(OntSoftware::class);
    }

    /**
     * Get the options for generating the slug.
     */
    public function getSlugOptions() : SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('name')
            ->saveSlugsTo('slug');
    }

    public function getFileAttribute()
    {
        return $this->getFirstMedia();
    }
}
