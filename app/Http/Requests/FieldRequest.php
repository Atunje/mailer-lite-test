<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;

class FieldRequest extends APIFormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        $rules = [
            'title' => 'required|string|max:255|unique:fields',
            'type' => 'required|in:date,number,string,boolean',
        ];

        //if field is to be updated, ignore the field in the unique rule
        $field_id = $this->route('field');
        if($field_id != null) {
            $rules['title'] = [
                'required',
                'string',
                'max:255',
                Rule::unique('fields')->ignore($field_id)
            ];
        }

        return $rules;
    }
}
