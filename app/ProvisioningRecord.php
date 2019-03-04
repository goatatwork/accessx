<?php

namespace App;

use OwenIt\Auditing\Auditable;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use App\GoldAccess\Dhcp\Contracts\Deployable;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;

class ProvisioningRecord extends Model implements HasMedia, AuditableContract, Deployable
{
    use HasMediaTrait, Auditable;

    protected $fillable = [
        'service_location_id',
        'ont_profile_id',
        'port_id',
        'ip_address_id',
        'len',
        'circuit_id',
        'notes'
    ];

    protected $appends = ['port_tag', 'file', 'is_suspended'];

    public $media_collections = [
        'dhcp_management_ip' => "ManagementIp"
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

    public function getDhcpStringAttribute()
    {
        $dhcp_string =
            ($this->ont_profile->ont_software->getFirstMedia()) ?
            ($this->ont_profile->ont_software->getFirstMedia())->getCustomProperty('dhcp_string') :
            'unknown';

        return 'ont_profiles/' .
            $this->ont_profile->ont_software->ont->slug .
            '/' .
            $this->ont_profile->ont_software->version .
            '/' .
            $this->ont_profile->slug .
            '/' .
            $dhcp_string;
    }

    public function getIsSuspendedAttribute()
    {
        return $this->ont_profile->name == 'Suspended';
    }

    /**
     * @return int The ID of the last used profile OR the ID of the first profile
     * for the software attached to this record, in the event that there is no
     * last used profile.
     */
    public function getPreviousProfileIdAttribute()
    {
        $update_audits = $this->audits()->whereEvent('updated')->orderBy('created_at', 'desc')->get();

        $profile_change_audit_record = $update_audits->first(function($audit, $key) {
            if (array_has($audit->getModified(), 'ont_profile_id')) {
                return $audit->new_values['ont_profile_id'] != $audit->old_values['ont_profile_id'];
            }
        });

        return $profile_change_audit_record ? $profile_change_audit_record->old_values['ont_profile_id'] : $this->ont_profile->ont_software->ont_profiles()->first()->id;
    }
}
