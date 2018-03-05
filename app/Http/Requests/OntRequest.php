<?php

namespace App\Http\Requests;

use App\Ont;
use Illuminate\Foundation\Http\FormRequest;

class OntRequest extends FormRequest
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
     * @return \App\Ont The new ONT
     */
    public function persist()
    {
        return Ont::create($this->all());
    }
}
