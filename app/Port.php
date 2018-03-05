<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Port extends Model
{
    use Provisionable;

    protected $fillable = ['port_number', 'notes'];

    protected $appends = ['has_provisioning_records', 'subscriber_id'];

    public function slot()
    {
        return $this->belongsTo(Slot::class);
    }

    public function getSubscriberIdAttribute()
    {
        return $this->slot->aggregator->name . '-' . $this->slot->slot_number . '-' . $this->port_number;
    }
}
