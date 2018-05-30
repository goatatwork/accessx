<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class UserRoleResource extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $role = $this->roles()->first();

        $role->load('permissions');

        return $role;
    }
}
