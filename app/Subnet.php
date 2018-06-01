<?php

namespace App;

use OwenIt\Auditing\Auditable;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;

class Subnet extends Model implements AuditableContract
{
    use Auditable;

    protected $fillable = [
        'comment',
        'network_address',
        'subnet_mask',
        'cidr',
        'start_ip',
        'end_ip',
        'routers',
        'broadcast_address',
        'dns_servers',
        'default_lease_time',
        'max_lease_time',
        'notes'
    ];

    public function dhcp_shared_network()
    {
        return $this->belongsTo(DhcpSharedNetwork::class);
    }

    public function ip_addresses()
    {
        return $this->hasMany(IpAddress::class);
    }
}
