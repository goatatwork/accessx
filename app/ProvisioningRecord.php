<?php

namespace App;

use App\GoldAccess\Dhcp\Contracts\Deployable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;


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
        'notes',
        'suspended'
    ];

    protected $appends = ['port_tag', 'file', 'is_suspended'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'suspended' => 'boolean',
    ];

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

    public function packages()
    {
        return $this->morphToMany(Package::class, 'packageable')
            ->withPivot(['created_at','updated_at'])->withTimestamps();
    }

    public function getCustomerName()
    {
        if ($this->service_location) {
            if ($this->service_location->customer) {
                return $this->service_location->customer->customer_name;
            }
            return null;
        }
        return null;
    }

    public function getDeets()
    {
        return [
            'customer_name' => $this->getCustomerName(),
        ];
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

    public function getFileAttribute()
    {
        return $this->getFirstMedia();
    }

    public function getIsSuspendedAttribute()
    {
        return $this->ont_profile->name == 'Suspended';
    }

    /*
     * @return App\Package::class|null
     */
    public function getPackageAttribute()
    {
        return $this->packages ?
            $this->packages->sortByDesc('pivot.created_at')->first() : null;
    }

    /*
     * @return App\Package::class|null
     */
    public function getPackageIdAttribute()
    {
        return $this->packages ?
            $this->packages->sortByDesc('pivot.created_at')->first()->id : null;
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
        $module = $this->port->module;
        $port = $this->port->port_number;
        return $aggregator . '-' . $slot . '-' . $module . '-' . $port;
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
