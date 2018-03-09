<?php

namespace App;

use Spatie\Sluggable\HasSlug;
use OwenIt\Auditing\Auditable;
use Spatie\Sluggable\SlugOptions;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;

class Platform extends Model implements AuditableContract
{
    use HasSlug, Auditable;

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
