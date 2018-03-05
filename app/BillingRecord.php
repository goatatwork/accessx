<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BillingRecord extends Model
{
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
