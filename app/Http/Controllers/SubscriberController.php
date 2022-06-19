<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\Subscriber;
use App\Models\Field;
use App\Http\Services\SubscriberService;
use Illuminate\Validation\Rule;

class SubscriberController extends Controller
{

    public function __construct(private readonly SubscriberService $subscriberService) {}


    public function show(Request $request) {
        $data = $this->subscriberService->findAll($request);
        return response()->json(['message' => 'Subscribers were successfully retrieved.', 'status'=>true, 'data'=>$data]);
    }


    public function view(int $id) {
        $data = $this->subscriberService->findOne($id);
        return response()->json(['message' => 'Subscribers were successfully retrieved.', 'status'=>true, 'data'=>$data]);
    }

    public function create(Request $request) {

        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:subscribers',
            'state' => 'required|in:active,unsubscribed,junk,bounced,unconfirmed',
        ];

        //get the extra fields 
        $fields = Field::all();

        foreach($fields as $field) {
            $type = ($field->type == 'number') ? 'numeric' : $field->type;
            $rules[$field->title] = 'nullable|'.$type;
        }

        $validator = Validator::make($request->all(), $rules);
 
        if ($validator->fails()) {
            return response()->json(['message' => join(" ", $validator->errors()->all()), 'status' => false], 400);
        } else {

            $inputs = $validator->validated();
            if($this->subscriberService->save($inputs)) {
                return response()->json(['message' => 'New subscriber was successfully created', 'status' => true], 201);
            }

        }

        return response()->json(['message' => 'There was an error creating subscriber!', 'status' => false], 400);
    }


    public function update(Subscriber $subscriber, Request $request) {

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'state' => 'required|in:active,unsubscribed,junk,bounced,unconfirmed',
            'email' => [
                'required',
                'email',
                Rule::unique('subscribers')->ignore($subscriber)
            ]
        ]);
 
        if ($validator->fails()) {
            return response()->json(['message' => join(" ", $validator->errors()->all()), 'status' => false], 400);
        } else {

            $inputs = $validator->validated();
            if($this->subscriberService->save($inputs, $subscriber)) {
                return response()->json(['message' => 'Subscriber was successfully updated', 'status' => true], 200);
            }

        }

        return response()->json(['message' => 'There was an error updating subscriber!', 'status' => false], 400);
    }


    public function delete(Subscriber $subscriber) {

        if($subscriber->delete()) {
            return response()->json(['message' => 'Subscriber was successfully deleted', 'status' => true], 200);
        }

        return response()->json(['message' => 'Subscriber could not be deleted', 'status' => false], 400);
    }


}
