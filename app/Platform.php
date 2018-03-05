<?php

namespace App;

use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use Illuminate\Database\Eloquent\Model;

class Platform extends Model
{
    use HasSlug;

    protected $fillable = [
        'name',
        'number_of_slots',
        'notes'
    ];

    public function module_types()
    {
        return $this->hasMany(ModuleType::class);
    }

    public function aggregators()
    {
        return $this->hasMany(Aggregator::class);
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
