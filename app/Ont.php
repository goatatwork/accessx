<?php

namespace App;

use Spatie\Sluggable\HasSlug;
use OwenIt\Auditing\Auditable;
use Spatie\Sluggable\SlugOptions;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;

class Ont extends Model implements HasMedia, AuditableContract
{
    use HasMediaTrait, HasSlug, Auditable;

    protected $fillable = [
        'model_number',
        'manufacturer',
        'indoor',
        'wifi',
        'number_of_ethernet_ports',
        'number_of_pots_lines',
        'notes',
        'oem'
    ];

    protected $appends = ['number_of_files', 'has_provisioning_records', 'number_of_software_versions'];

    public function ont_software()
    {
        return $this->hasMany(OntSoftware::class);
    }

    /**
     * Get the options for generating the slug.
     */
    public function getSlugOptions() : SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom(['manufacturer', 'model_number'])
            ->saveSlugsTo('slug');
    }

    public function getNumberOfFilesAttribute()
    {
        return $this->media()->count();
    }

    public function getHasProvisioningRecordsAttribute()
    {
        return $this->ont_software()->get()->where('has_provisioning_records', true)->count() ? true : false;
    }

    public function getNumberOfSoftwareVersionsAttribute()
    {
        return $this->ont_software()->exists() ? $this->ont_software()->count() : 0;
    }
}
