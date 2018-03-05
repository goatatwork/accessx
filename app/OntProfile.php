<?php

namespace App;

use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\MediaLibrary\HasMedia\Interfaces\HasMedia;

class OntProfile extends Model implements HasMedia
{
    use HasMediaTrait, Provisionable, HasSlug;

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
