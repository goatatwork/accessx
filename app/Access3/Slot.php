<?php

namespace App\Access3;

use Illuminate\Database\Eloquent\Model;

// use OwenIt\Auditing\Auditable;

class Slot extends Model
{
    // use Auditable;

    protected $connection = 'mysql_access3';

    protected $fillable = ['slot_number'];

    protected $appends = ['is_populated'];

    public function aggregator()
    {
        return $this->belongsTo(Aggregator::class);
    }

    public function module_type()
    {
        return $this->belongsTo(ModuleType::class);
    }

    public function ports()
    {
        return $this->hasMany(Port::class);
    }

    public function provisioned_ports()
    {
        return $this->hasMany(Port::class, 'slot_id')->has('provisioning_records');
    }

    public function non_provisioned_ports()
    {
        return $this->hasMany(Port::class, 'slot_id')->doesntHave('provisioning_records');
    }

    public function getIsPopulatedAttribute()
    {
        return $this->module_type()->exists();
    }

    public function scopePopulated($query)
    {
        return $query->get()->where('is_populated', true);
    }
}
