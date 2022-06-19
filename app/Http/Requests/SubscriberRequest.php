<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
use App\Models\Field;
use Illuminate\Validation\Rule;

class SubscriberRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {

        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:subscribers',
            'state' => 'required|in:active,unsubscribed,junk,bounced,unconfirmed',
        ];

        //validate the inputs of the other fields
        $fields = Field::all();
        foreach($fields as $field) {
            $type = ($field->type == 'number') ? 'numeric' : $field->type;
            $rules[$field->title] = 'nullable|'.$type;
        }

        //if it's an update request
        $subscriber_id = $this->route('subscriber');
        if($subscriber_id != null) {
            $rules['email'] = [
                'required',
                'email',
                Rule::unique('subscribers')->ignore($subscriber_id)
            ];
        }

        return $rules;
    }


    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'status' => false,
            'message' => $validator->errors()
        ], 422));
    }

}
