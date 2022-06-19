<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Field;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\FieldRequest;

class FieldController extends Controller
{
    public function show(Request $request) 
    {
        return $this->successResponse('Fields were successfully retrieved', Field::all(), 'fields');
    }

    public function create(FieldRequest $request) 
    {
        $inputs = $request->validated();
        if(Field::create($inputs)) 
            return $this->successResponseNoData('New field was successfully created', 201);
        
        return $this->failureResponse('There was an error registering!');
    }


    public function update(Field $field, FieldRequest $request) 
    {
        $inputs = $request->validated();
        if($field->update($inputs)) 
            return $this->successResponseNoData('New field was successfully created');
            
        return $this->failureResponse('There was an error registering!');
    }


}
