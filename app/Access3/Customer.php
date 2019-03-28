<?php

namespace App\Access3;

use Illuminate\Database\Eloquent\Model;

// use OwenIt\Auditing\Auditable;

class Customer extends Model
{
    // use Auditable;

    /**
     * The connection name for the model.
     *
     * @var string
     */
    protected $connection = 'mysql_access3';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'customers';

    protected $fillable = [
        'company',
        'firstname',
        'lastname',
        'email',
        'phone1',
        'phone2',
        'billing_address1',
        'billing_address2',
        'billing_city',
        'billing_state',
        'billing_zip',
        'billing_phone',
    ];

    protected $appends = ['full_name', 'customer_type', 'customer_name'];

    public function locations()
    {
        return $this->hasMany(ServiceLocation::class, 'customer_id');
    }

    public function provisioning_records()
    {
        return $this->hasManyThrough(ProvisioningRecord::class, ServiceLocation::class);
    }

    public function getFullNameAttribute()
    {
        return $this->firstname . ' ' . $this->lastname;
    }

    public function getCustomerNameAttribute()
    {
        if (is_null($this->company) || empty($this->company))
        {
            return $this->firstname . ' ' . $this->lastname;
        } else {
            return $this->company;
        }
    }

    public function getCustomerTypeAttribute()
    {
        if (is_null($this->company) || empty($this->company))
        {
            return 'Residential';
        }
        return 'Business';
    }
}
