<?php

namespace App\Access3;

trait Provisionable
{
    public function provisioning_records()
    {
        return $this->hasMany(ProvisioningRecord::class);
    }

    /**
     * @return true/false
     */
    public function getHasProvisioningRecordsAttribute()
    {
        return $this->provisioning_records()->where($this->getForeignKey(), $this->id)->exists();
    }

    public function scopeProvisioned($query)
    {
        return $query->has('provisioning_records');
    }

    public function scopeProvisionedWithDetails($query)
    {
        return $query->has('provisioning_records')->with(['provisioning_records' => function($query) {
            return $query->fullDetails()->get();
        }]);
    }

    public function scopeNotProvisioned($query)
    {
        return $query->doesntHave('provisioning_records');
    }
}
