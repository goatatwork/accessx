<?php

namespace App\Access3;

use Illuminate\Database\Eloquent\Model;

use App\Jobs\ResumeService;
use App\Jobs\SuspendService;
use App\Jobs\FactoryResetOnt;
// use OwenIt\Auditing\Auditable;

class ProvisioningRecord extends Model
{
    // use Auditable;

    /**
     * The connection name for the model.
     *
     * @var string
     */
    protected $connection = 'mysql_access3';


    protected $fillable = [
        'service_location_id',
        'ont_config_id',
        'port_id',
        'dhcp_ip_address_id',
        'len',
    ];

    protected $appends = ['suspended'];

    public function service_location()
    {
        return $this->belongsTo(ServiceLocation::class);
    }

    public function ont_config()
    {
        return $this->belongsTo(OntConfig::class);
    }

    public function port()
    {
        return $this->belongsTo(Port::class);
    }

    public function dhcp_ip_address()
    {
        return $this->belongsTo(DhcpIpAddress::class);
    }

    public function dhcp_reservations()
    {
        return $this->hasMany(DhcpReservation::class);
    }

    public function suspension()
    {
        return $this->hasOne(Suspension::class);
    }

    public function factoryReset()
    {
        dispatch(new FactoryResetOnt($this, auth()->user()));
    }

    public function getSuspendedAttribute()
    {
        return $this->suspension()->where($this->getForeignKey(), $this->id)->exists();
    }

    public function suspend($notes = '')
    {
        $this->suspension()->create(['notes' => $notes]);
        dispatch((new SuspendService($this, auth()->user()))->onQueue('suspensions'));
        return $this;
    }

    public function unsuspend()
    {
        $this->suspension()->delete();
        dispatch((new ResumeService($this, auth()->user()))->onQueue('suspensions'));
        return $this;
    }

    /**
     * @return collection
     */
    public function scopeWithDetails($query)
    {
        return $query->with([
            'service_location',
            'service_location.customer'
        ])->with([
            'ont_config',
            'ont_config.software',
            'ont_config.software.ont' => function($query) {
                $query->with('meta');
            }
        ])->with([
            'port',
            'port.slot' => function($query) {
                $query->with('module_type');
            },
            'port.slot.aggregator' => function($query) {
                $query->with('olt_type');
            }
        ])->with([
            'dhcp_ip_address',
            'dhcp_ip_address.subnet',
            'dhcp_ip_address.subnet.shared_network'
        ])->with([
            'dhcp_reservations',
            'dhcp_reservations.dhcp_ip_address'
        ])->with(['suspension']);
    }

    /**
     * @return collection
     */
    public function scopeWithDetailsById($query)
    {
        return $query->with([
            'service_location',
            'service_location.customer'
        ])->with([
            'ont_config',
            'ont_config.software',
            'ont_config.software.ont' => function($query) {
                $query->with('meta');
            }
        ])->with([
            'port',
            'port.slot' => function($query) {
                $query->with('module_type');
            },
            'port.slot.aggregator' => function($query) {
                $query->with('olt_type');
            }
        ])->with([
            'dhcp_ip_address',
            'dhcp_ip_address.subnet',
            'dhcp_ip_address.subnet.shared_network'
        ])->with([
            'dhcp_reservations',
            'dhcp_reservations.dhcp_ip_address'
        ])->with(['suspension'])
        ->where('id', $this->id)->first();
    }
}
