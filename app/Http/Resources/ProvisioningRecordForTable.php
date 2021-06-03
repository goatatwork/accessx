<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class ProvisioningRecordForTable extends Resource
{

    /**
     * Works with components/GoldAccess/Customers/*
     */

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'record_url' => '/provisioning/'.$this->id,
            'customer' => $this->service_location->customer->customer_name,
            'customer_url' => '/customers/'.$this->service_location->customer->id,
            'customer_type' => $this->service_location->customer->customer_type,
            'address' => $this->service_location->address1,
            'service_location_url' => '/provisioning/service_locations/'.$this->service_location->id.'/show',
            'package' => $this->ont_profile->name,
            'management_ip' => $this->ip_address->address,
            'ont' => $this->ont_profile->ont_software->ont->model_number,
            'aggregator' => $this->port->slot->aggregator->name,
            'aggregator_url' => '/infrastructure/aggregators/' . $this->port->slot->aggregator->id,
            'slot' => $this->port->slot->slot_number,
            'port' => $this->port->port_number,
            'len' => $this->len,
            'google_maps_embed_api_string' => $this->service_location->google_maps_embed_api_string
        ];
    }
}