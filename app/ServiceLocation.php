<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ServiceLocation extends Model
{
    use Provisionable;

    protected $fillable = [
        'name',
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

    protected $appends = ['has_provisioning_records'];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function getGoogleMapsEmbedApiStringAttribute()
    {
        $api_key = 'AIzaSyA2wKTMVVf4ssDe3SqzEa2An-mdESTBPZo';

        $searchUrl = 'https://www.google.com/maps/embed/v1/search?q=';

        $query_string_parts = $this->address1 . ' ' . $this->city . ' ' . $this->state . ' ' . $this->zip;

        $address_query_string = str_replace('-', '%20', str_slug($query_string_parts, '-'));

        return $searchUrl . $address_query_string . '&key=' . $api_key;
    }
}
