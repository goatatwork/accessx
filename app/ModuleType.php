<?php

namespace App;

use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use Illuminate\Database\Eloquent\Model;

class ModuleType extends Model
{
    use HasSlug;

    protected $fillable = [
        'name',
        'number_of_ports',
        'notes'
    ];

    public function platform()
    {
        return $this->belongsTo(Platform::class);
    }

    public function slots()
    {
        return $this->hasMany(Slot::class);
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
}
