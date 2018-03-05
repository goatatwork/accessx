<?php

namespace App\Http\Requests;

use App\Aggregator;
use Illuminate\Foundation\Http\FormRequest;

class AggregatorRequest extends FormRequest
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
     * @return  \App\Aggregator
     */
    public function persist()
    {
        \Log::info($this->all());
        return Aggregator::create($this->all());
    }
}
