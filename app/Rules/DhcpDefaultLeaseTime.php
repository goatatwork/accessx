<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class DhcpDefaultLeaseTime implements Rule
{
    /**
     * Create a new rule instance.
     * @param string $setting_name
     * @return void
     */
    public function __construct()
    {
        $this->message = 'You must provide a valid dhcp lease time.';
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
        if ($value == 'infinite') {
            return true;
        } elseif (ends_with($value, 'm')) {
            $this->message = 'The minimum number of minutes for a dhcp lease is 2.';
            $number = str_replace_last('m', '', $value);
            return $number >= 2;
        } elseif (ends_with($value, 'h')) {
            $this->message = 'The minimum number of hours for a dhcp lease is 1.';
            $number = str_replace_last('h', '', $value);
            return $number >= 1;
        } else {
            $this->message = 'The minimum number of seconds for a dhcp lease is 120.';
            return $value >= 120;
        }
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return $this->message;
    }
}
