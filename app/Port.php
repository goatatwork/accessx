<?php

namespace App;

use OwenIt\Auditing\Auditable;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;

class Port extends Model implements AuditableContract
{
    use Provisionable, Auditable;

    protected $fillable = ['port_number', 'notes', 'module', 'port_name'];

    protected $appends = ['has_provisioning_records'];

    public function slot()
    {
        return $this->belongsTo(Slot::class);
    }
}
