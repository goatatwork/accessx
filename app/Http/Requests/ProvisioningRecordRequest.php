<?php

namespace App\Http\Requests;

use App\ProvisioningRecord;
use Illuminate\Foundation\Http\FormRequest;

class ProvisioningRecordRequest extends FormRequest
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
            'service_location_id' => 'required',
            'ont_profile_id' => 'required',
            'port_id' => 'required',
            'ip_address_id' => 'required',
        ];
    }

    /**
     * @return  \App\ProvisioningRecord The new ProvisioningRecord
     */
    public function persist()
    {
        return ProvisioningRecord::create($this->all());
    }
}
