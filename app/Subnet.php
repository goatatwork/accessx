<?php

namespace App;

use OwenIt\Auditing\Auditable;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;

class Subnet extends Model implements AuditableContract, HasMedia
{
    use Auditable, HasMediaTrait;

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

    protected $appends = ['has_provisioning_records', 'has_option_43', 'slug'];

    public $media_collections = [
        'dhcp_subnet_definition' => "Option0",
        'dhcp_subnet_option43' => "Option43",
    ];

    public function allMediaCollections()
    {
        return collect(array_keys($this->media_collections));
    }

    public function registerMediaCollections() {
        foreach (array_keys($this->media_collections) as $collection) {
            $this->addMediaCollection($collection)->singleFile();
        }
    }

    public function dhcp_shared_network()
    {
        return $this->belongsTo(DhcpSharedNetwork::class);
    }

    public function ip_addresses()
    {
        return $this->hasMany(IpAddress::class);
    }

    public function provisioning_records()
    {
        return $this->hasManyThrough(ProvisioningRecord::class, IpAddress::class);
    }

    public function getHasProvisioningRecordsAttribute()
    {
        return $this->provisioning_records()->exists();
    }

    public function getIsManagementAttribute()
    {
        return ($this->dhcp_shared_network) ? $this->dhcp_shared_network->management : null;
    }

    public function getHasOption43Attribute()
    {
        return ($this->getFirstMedia('dhcp_subnet_option43')) ? true : false;
    }

    public function getSlugAttribute()
    {
        return ($this->dhcp_shared_network) ?
            $this->dhcp_shared_network->slug . '-' . preg_replace('/\./', '_', $this->network_address . '_' . $this->cidr) :
                'unknown-' . preg_replace('/\./', '_', $this->network_address . '_' . $this->cidr);
    }
}
