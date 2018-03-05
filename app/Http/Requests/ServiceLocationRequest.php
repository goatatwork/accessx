<?php

namespace App\Http\Requests;

use App\Customer;
use App\ServiceLocation;
use Illuminate\Foundation\Http\FormRequest;

class ServiceLocationRequest extends FormRequest
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
            //
        ];
    }

    /**
     * @return \App\ServiceLocation The new ServiceLocation
     */
    public function persist()
    {
        $customer = Customer::find($this->customer['id']);
        return $customer->service_locations()->create($this->all());
    }
}
