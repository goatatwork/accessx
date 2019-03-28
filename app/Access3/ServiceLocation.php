<?php

namespace App\Access3;

use Illuminate\Database\Eloquent\Model;

// use OwenIt\Auditing\Auditable;

class ServiceLocation extends Model
{
    // use Provisionable, Auditable;
    // use Provisionable;

    protected $connection = 'mysql_access3';

    protected $table = 'service_locations';

    protected $appends = ['has_provisioning_records'];

    protected $fillable = [
        'name',
        'phone',
        'address1',
        'address2',
        'city',
        'state',
        'zip',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}
