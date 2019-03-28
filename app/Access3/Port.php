<?php

namespace App\Access3;

use Illuminate\Database\Eloquent\Model;

// use OwenIt\Auditing\Auditable;

class Port extends Model
{
    // use Provisionable, Auditable;
    use Provisionable;

    protected $connection = 'mysql_access3';

    protected $fillable = ['port_number'];

    protected $appends = ['has_provisioning_records'];

    public function slot()
    {
        return $this->belongsTo(Slot::class);
    }
}
