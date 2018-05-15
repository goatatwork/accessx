<?php

namespace App\Http\Resources;

use App\Ont;
use Illuminate\Http\Resources\Json\Resource;

class ProvisioningRecordForEditingResource extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        // return parent::toArray($request);
        return [
            'id' => $this->id,
            'ont' => $this->ont_profile->ont_software->ont,
            'ont_software' => $this->ont_profile->ont_software,
            'ont_profile' => $this->ont_profile,
            'aggregator' => $this->port->slot->aggregator,
            'slot' => $this->port->slot,
            'port' => $this->port,
            'shared_network' => $this->ip_address->subnet->dhcp_shared_network,
            'subnet' => $this->ip_address->subnet,
            'ip' => $this->ip_address,
        ];
    }
}
