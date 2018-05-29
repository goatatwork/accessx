<?php

namespace App\Http\Requests;

use App\User;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class EditUserRequest extends FormRequest
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
        if ( !$this->reset_password ) {

            array_except($this, ['password', 'password_confirmation']);

            return [
                'name' => 'required|max:128',
                'email' => 'required|unique:users,email,'.$this->id
            ];

        }

        return [
            'name' => 'required|max:128',
            'email' => 'required|unique:users,email,'.$this->id,
            'password' => 'required|confirmed',
        ];
    }

    public function persist(User $user)
    {
        if ($this->reset_password) {
            return tap($user)->update([
                'name' => $this->name,
                'email' => $this->email,
                'password' => bcrypt($this->password)
            ]);
        } else {
            return tap($user)->update($this->all());
        }
    }
}
