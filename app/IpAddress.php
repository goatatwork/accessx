<?php

namespace App;

use OwenIt\Auditing\Auditable;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;

class IpAddress extends Model implements AuditableContract
{
    use Provisionable, Auditable;

    protected $fillable = ['address', 'vlan', 'exclude_from_dhcp'];

    // protected $appends = ['has_provisioning_records'];

    public function subnet()
    {
        return $this->belongsTo(Subnet::class);
    }
}
