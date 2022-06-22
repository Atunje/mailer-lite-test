<?php

namespace App\Http\Requests;

use App\Models\Subscriber;

class BulkDeleteRequest extends APIFormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'subscribers' => 'required|array',
            'subscribers.*' => 'integer|exists:subscribers,id'
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'subscribers.*.integer' => 'Invalid subscriber selected',
            'subscribers.*.exists' => 'Selected subscriber does not exist!',
        ];
    }

}
