<?php

namespace App;

use App\ProvisioningRecord;

trait Provisionable
{
    public function provisioning_records()
    {
        return $this->hasMany(ProvisioningRecord::class);
    }

    /**
     * @return boolean
     */
    public function getHasProvisioningRecordsAttribute()
    {
        return $this->provisioning_records()->where($this->getForeignKey(), $this->id)->exists();
    }

    /**
     * @return boolean
     */
    public function scopeProvisioned($query)
    {
        return $query->has('provisioning_records');
    }

    /**
     * @return boolean
     */
    public function scopeNotProvisioned($query)
    {
        return $query->doesntHave('provisioning_records');
    }
}
