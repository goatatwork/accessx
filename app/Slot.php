<?php

namespace App;

use App\ModuleType;
use OwenIt\Auditing\Auditable;
use App\Exceptions\SlotAlreadyHasPorts;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;

class Slot extends Model implements AuditableContract
{
    use Auditable;

    protected $fillable = [
        'module_type_id',
        'slot_number',
        'notes'
    ];

    protected $appends = ['populated', 'has_provisioning_records'];

    public function aggregator()
    {
        return $this->belongsTo(Aggregator::class);
    }

    public function module_type()
    {
        return $this->belongsTo(ModuleType::class);
    }

    public function ports()
    {
        return $this->hasMany(Port::class);
    }

    public function provisioning_records()
    {
        return $this->hasManyThrough(ProvisioningRecord::class, Port::class);
    }

    public function getPopulatedAttribute()
    {
        return $this->module_type_id ? true : false;
    }

    public function populate(ModuleType $module_type)
    {
        $this->module_type_id = $module_type->id;
        $this->save();
        $this->createPorts();
        return $this->load('ports');
    }

    /**
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePopulatedOnly($query)
    {
        return $query->get()->where('populated', true);
    }

    /**
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeUnpopulatedOnly($query)
    {
        return $query->get()->where('populated', false);
    }

    public function unpopulate()
    {
        $this->module_type_id = null;
        $this->save();
        $this->ports()->delete();
        return $this;
    }

    protected function createPorts()
    {
        if ($this->ports()->count() == 0)
        {
            for ($port_number = 1; $port_number <= $this->module_type->number_of_ports; $port_number++)
            {
                $this->ports()->create(['port_number' => $port_number]);
            }
        } else {
            throw new SlotAlreadyHasPorts();
        }
    }

    public function getHasProvisioningRecordsAttribute()
    {
        return $this->provisioning_records()->exists();
    }
}
