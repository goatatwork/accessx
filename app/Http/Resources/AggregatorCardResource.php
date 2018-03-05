<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class AggregatorCardResource extends Resource
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
            'number_of_slots' => $this->platform->number_of_slots,
            'populated_slots' => $this->slots()->populatedOnly()->count(),
            'unpopulated_slots' => $this->slots()->unpopulatedOnly()->count(),
            'number_of_ports' => $this->ports()->count(),
        ];
    }
}
