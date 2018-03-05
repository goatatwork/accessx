<?php

namespace App;

use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use Illuminate\Database\Eloquent\Model;

class DhcpSharedNetwork extends Model
{
    use HasSlug;

    protected $fillable = [
        'name',
        'management',
        'vlan',
        'notes'
    ];

    public function subnets()
    {
        return $this->hasMany(Subnet::class);
    }

    public function ip_addresses()
    {
        return $this->hasManyThrough(IpAddress::class, Subnet::class);
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
