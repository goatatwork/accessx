<?php

namespace App\Access3;

use Illuminate\Database\Eloquent\Model;

// use OwenIt\Auditing\Auditable;

class Aggregator extends Model
{
    // use Auditable;

    protected $connection = 'mysql_access3';

    protected $fillable = [
            'name',
            'hostname',
            'management_ip',
            'monitoring_ip',
            'mac'
    ];

    public function olt_type()
    {
        return $this->belongsTo(OltType::class);
    }

    public function slots()
    {
        return $this->hasMany(Slot::class);
    }

    public function populated_slots()
    {
        return $this->hasMany(Slot::class, 'aggregator_id')->has('module_type')->with('module_type');
    }
}
