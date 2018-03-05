<?php

namespace App\Http\Requests;

use App\Customer;
use Illuminate\Foundation\Http\FormRequest;

class NewCustomerFormRequest extends FormRequest
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
            'first_name' => 'required'
        ];
    }

    public function persist()
    {
        $customer = Customer::create($this->customerFields());
        $customer->service_locations()->create($this->serviceLocationFields());
        $customer->billing_record()->create($this->billingRecordFields());
        return $customer;
    }

    /**
     * @return array Fields appropriate for a Customer::create()
     */
    protected function customerFields()
    {
        return [
            'company_name' => $this->company_name ? $this->company_name : '',
            'first_name' => $this->first_name ? $this->first_name : '',
            'last_name' => $this->last_name ? $this->last_name : '',
        ];
    }

    /**
     * @return  array Fields appropriate for a ServiceLocation::create()
     */
    protected function serviceLocationFields()
    {
        return [
        'name' => 'Default',
        'poc_name' => $this->first_name . ' ' . $this->last_name,
        'poc_email' => $this->email,
        'phone1' => $this->phone1,
        'phone2' => $this->phone2,
        'address1' => $this->address1,
        'address2' => $this->address2,
        'city' => $this->city,
        'state' => $this->state,
        'zip' => $this->zip,
        'notes' => $this->notes,
        ];
    }

    /**
     * @return  array Fields appropriate for a BillingRecord::create()
     */
    protected function billingRecordFields()
    {
        return [
            'poc_name' => $this->use_same_address_for_billing ? $this->first_name . ' ' . $this->last_name : $this->billing_contact_name,
            'poc_email' => $this->use_same_address_for_billing ? $this->email : $this->billing_contact_email,
            'phone1' => $this->use_same_address_for_billing ? $this->phone1 : $this->billing_phone1,
            'phone2' => $this->use_same_address_for_billing ? $this->phone2 : $this->billing_phone2,
            'address1' => $this->use_same_address_for_billing ? $this->address1 : $this->billing_address1,
            'address2' => $this->use_same_address_for_billing ? $this->address2 : $this->billing_address2,
            'city' => $this->use_same_address_for_billing ? $this->city : $this->billing_city,
            'state' => $this->use_same_address_for_billing ? $this->state : $this->billing_state,
            'zip' => $this->use_same_address_for_billing ? $this->zip : $this->billing_zip,
            'notes' => $this->use_same_address_for_billing ? $this->notes : $this->billing_notes,
        ];
    }
}
