<?php

namespace App\Rules;

use App\OntSoftware;
use Illuminate\Contracts\Validation\Rule;

class OneSuspendedProfilePerSoftwareVersion implements Rule
{
    /**
     * @var \App\OntSoftware
     */
    public $ont_software;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct(OntSoftware $ont_software)
    {
        $this->ont_software = $ont_software;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        return ! $this->ont_software->ont_profiles()->whereName($value)->exists();
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'A profile with that name already exists for this software version.';
    }
}
