<?php

namespace App;

use Spatie\Sluggable\HasSlug;
use OwenIt\Auditing\Auditable;
use Spatie\Sluggable\SlugOptions;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;

class DhcpSharedNetwork extends Model implements AuditableContract
{
    use HasSlug, Auditable;

    protected $fillable = [
        'name',
        'management',
        'vlan',
        'notes'
    ];

    protected $appends = ['has_provisioning_records'];

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

    public function getHasProvisioningRecordsAttribute()
    {
        return $this->subnets()->get()->where('has_provisioning_records', true)->count() ? true : false;
    }
}
