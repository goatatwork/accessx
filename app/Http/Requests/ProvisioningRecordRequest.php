<?php

namespace App\Http\Requests;

use App\Jobs\SetRateLimit;
use App\Jobs\SetSubscriberId;
use App\Package;
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
        if ($this->method() == 'POST') {
            return [
                'service_location_id' => 'required',
                'ont_profile_id' => 'required',
                'port_id' => 'required',
                'ip_address_id' => 'required',
            ];
        }

        return [
            //
        ];
    }

    /**
     * @return  \App\ProvisioningRecord The new ProvisioningRecord
     */
    public function persist()
    {
        $pr = ProvisioningRecord::create($this->all());

        return $pr;
    }

    /**
     * @param  \App\ProvisioningRecord $pr
     *
     * @return  \App\ProvisioningRecord The new ProvisioningRecord
     */
    public function persistUpdate(ProvisioningRecord $pr)
    {
        if ($this->port_id != $pr->port_id) {
            SetSubscriberId::dispatch($pr);
        }

        /* This would indicate a new package was selected on the change package modal */
        if ($this->package_change) {
            SetRateLimit::dispatch($this->package_id, $pr);
            return ['success' => true];
        }

        $pr = tap($pr)->update($this->all());

        /* This would indicate a new package was selected on the edit provisioning record form */
        if ($this->package_id) {
            if ($this->package_id != $pr->package->id) {
                $package = Package::find($this->package_id);
                $pr->packages()->save($package);

                SetRateLimit::dispatch($this->package_id, $pr);
            }
        }

        return $pr;
    }
}
