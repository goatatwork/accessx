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

    protected $appends = [
        'customer_name',
        'customer_type',
        'created_at_for_humans',
        'has_provisioning_records',
        // 'number_of_provisioning_records',
        // 'has_service_locations',
        // 'number_of_service_locations',
    ];

    public function billing_record()
    {
        return $this->hasOne(BillingRecord::class);
    }

    public function getCreatedAtForHumansAttribute()
    {
        return $this->created_at->diffForHumans();
    }

    public function service_locations()
    {
        return $this->hasMany(ServiceLocation::class);
    }

    public function provisioning_records()
    {
        return $this->hasManyThrough(ProvisioningRecord::class, ServiceLocation::class);
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
        return $this->provisioning_records()->exists();
    }

    public function getNumberOfProvisioningRecordsAttribute()
    {
        return $this->provisioning_records()->count();
    }

    public function getHasServiceLocationsAttribute()
    {
        return $this->service_locations()->exists();
    }

    public function getNumberOfServiceLocationsAttribute()
    {
        return $this->service_locations()->count();
    }
}
