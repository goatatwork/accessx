<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\MediaLibrary\HasMedia\Interfaces\HasMedia;

class ProvisioningRecord extends Model implements HasMedia
{
    use HasMediaTrait;

    protected $fillable = [
        'service_location_id',
        'ont_profile_id',
        'port_id',
        'ip_address_id',
        'len',
        'circuit_id',
        'notes'
    ];

    protected $appends = ['port_tag', 'port_tag_unique', 'file'];

    public function service_location() {
        return $this->belongsTo(ServiceLocation::class);
    }

    public function ont_profile()
    {
        return $this->belongsTo(OntProfile::class);
    }

    public function port()
    {
        return $this->belongsTo(Port::class);
    }

    public function ip_address()
    {
        return $this->belongsTo(IpAddress::class);
    }

    public function getFileAttribute()
    {
        return $this->getFirstMedia();
    }

    /**
     * Get the port tag associated with this provisioning record. This will
     * also be used as the DHCP Option82 subscriber-id
     * @return string The port tag
     */
    public function getPortTagAttribute()
    {
        $aggregator = $this->port->slot->aggregator->slug;
        $slot = $this->port->slot->slot_number;
        $port = $this->port->port_number;
        return $aggregator . '-' . $slot . '-' . $port;
    }

    /**
     * Similar to getPortTagAttribute, but this includes the provisioning
     * record's model ID to make the string a little more unique. This is
     * useful for things such as naming dhcp config files.
     * @return string The port tag with model ID attached
     */
    public function getPortTagUniqueAttribute()
    {
        return $this->getPortTagAttribute() . '-' . $this->id;
    }
}
