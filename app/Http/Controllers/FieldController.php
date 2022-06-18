<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Field;
use Illuminate\Support\Facades\Validator;

class FieldController extends Controller
{
    public function show(Request $request) {
        $data = ['fields'=>Field::all()];
        return response()->json(['message' => 'Fields were successfully retrieved.', 'status'=>true, 'data'=>$data]);
    }

    public function create(Request $request) {

        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'type' => 'required|in:date,number,string,boolean',
        ]);
 
        if ($validator->fails()) {
            return response()->json(['message' => join(" ", $validator->errors()->all()), 'status' => false], 400);
        } else {

            $inputs = $validator->validated();
            if(Field::create($inputs)) {
                return response()->json(['message' => 'New field was successfully created', 'status' => true], 201);
            }

        }

        return response()->json(['message' => 'There was an error registering!', 'status' => false], 400);
    }


    public function update(Field $field, Request $request) {

        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'type' => 'required|in:date,number,string,boolean',
        ]);
 
        if ($validator->fails()) {
            return response()->json(['message' => join(" ", $validator->errors()->all()), 'status' => false], 400);
        } else {

            $inputs = $validator->validated();
            if($field->update($inputs)) {
                return response()->json(['message' => 'New field was successfully created', 'status' => true], 200);
            }

        }

        return response()->json(['message' => 'There was an error registering!', 'status' => false], 400);
    }


}
