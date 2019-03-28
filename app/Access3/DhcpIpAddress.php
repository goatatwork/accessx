<?php

namespace App\Access3;

use Illuminate\Database\Eloquent\Model;

// use OwenIt\Auditing\Auditable;

class DhcpIpAddress extends Model
{
    // use Provisionable, Auditable;
    use Provisionable;

    /**
     * The connection name for the model.
     *
     * @var string
     */
    protected $connection = 'mysql_access3';


    protected $fillable = ['address'];

    protected $appends = ['has_provisioning_records', 'reserved'];

    public function subnet()
    {
        return $this->belongsTo(DhcpSubnet::class, 'dhcp_subnet_id');
    }

    public function dhcp_reservation()
    {
        return $this->hasOne(DhcpReservation::class);
    }

    public function getReservedAttribute()
    {
        return $this->dhcp_reservation()->where($this->getForeignKey(), $this->id)->exists();
    }

    public function scopeReserved($query)
    {
        return $query->has('dhcp_reservation');
    }

    public function scopeNotReserved($query)
    {
        return $query->doesntHave('dhcp_reservation');
    }
}
