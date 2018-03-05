<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class IpAddress extends Model
{
    use Provisionable;

    protected $fillable = ['address', 'vlan'];

    protected $appends = ['has_provisioning_records'];

    public function subnet()
    {
        return $this->belongsTo(Subnet::class);
    }
}
