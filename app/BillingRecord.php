<?php

namespace App;

use OwenIt\Auditing\Auditable;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;

class BillingRecord extends Model implements AuditableContract
{
    use Auditable;

    protected $fillable = [
        'poc_name',
        'poc_email',
        'phone1',
        'phone2',
        'address1',
        'address2',
        'city',
        'state',
        'zip',
        'notes',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::all());
    }
}
