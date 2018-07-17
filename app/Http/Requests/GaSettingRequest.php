<?php

namespace App\Http\Requests;

use App\Rules\DhcpDefaultLeaseTime;
use Illuminate\Foundation\Http\FormRequest;

class GaSettingRequest extends FormRequest
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
        if ($this->name == 'dhcp_default_lease_time') {
            return [
                'name' => 'required',
                'value' => ['required', new DhcpDefaultLeaseTime]
            ];
        }

        return [
            //
        ];
    }

    /**
     * Custom messages
     * @return  array
     */
    public function messages()
    {
        return [
            'name.required' => 'There was no setting provided.',
            'value.required' => $this->name . ' requires a value.'
        ];
    }

}
