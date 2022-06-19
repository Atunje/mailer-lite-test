<?php

namespace App\Http\Requests;

use App\Models\Field;
use Illuminate\Validation\Rule;

class SubscriberRequest extends APIFormRequest
{
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

}
