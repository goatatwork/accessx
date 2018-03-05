<?php

namespace App\Http\Requests;

use App\DhcpSharedNetwork;
use Illuminate\Foundation\Http\FormRequest;

class DhcpSharedNetworkRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|unique:dhcp_shared_networks',
        ];
    }

    /**
     * @return \App\DhcpSharedNetwork [<description>]
     */
    public function persist()
    {
        return DhcpSharedNetwork::create($this->all());
    }
}
