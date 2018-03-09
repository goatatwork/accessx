<?php

namespace App;

use Spatie\Sluggable\HasSlug;
use OwenIt\Auditing\Auditable;
use Spatie\Sluggable\SlugOptions;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;

class Customer extends Model implements AuditableContract
{
    use HasSlug, Auditable;

    protected $fillable = [
        'company_name',
        'first_name',
        'last_name',
        'notes'
    ];

    protected $appends = ['customer_name', 'customer_type', 'has_provisioning_records'];

    public function billing_record()
    {
        return $this->hasOne(BillingRecord::class);
    }

    public function service_locations()
    {
        return $this->hasMany(ServiceLocation::class);
    }

    /**
     * Get the options for generating the slug.
     */
    public function getSlugOptions() : SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom(['company_name', 'first_name', 'last_name'])
            ->saveSlugsTo('slug');
    }

    public function getCustomerNameAttribute()
    {
        return $this->company_name ? $this->company_name : $this->first_name . ' ' . $this->last_name;
    }

    public function getCustomerTypeAttribute()
    {
        return $this->company_name ? 'Business' : 'Residential';
    }

    public function getHasProvisioningRecordsAttribute()
    {
        return ($this->service_locations()->provisioned()->count() > 0) ? true : false;
    }
}
