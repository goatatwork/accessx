<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class DnsmasqServerStatusCardResource extends Resource
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
            'isUp' => $this->isUp(),
        ];
    }

    protected function isUp()
    {
        return app('dockerbot')->containerIsRunning(config('goldaccess.dockerbot.services.dhcp.container_name'));
    }
}

