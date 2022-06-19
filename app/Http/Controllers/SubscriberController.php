<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Subscriber;
use App\Http\Services\SubscriberService;
use App\Http\Requests\SubscriberRequest;


class SubscriberController extends Controller
{

    public function __construct(private readonly SubscriberService $subscriberService) {}


    public function show(Request $request) 
    {
        return $this->successResponse('Subscribers were successfully retrieved', $this->subscriberService->findAll($request));
    }


    public function view(int $id) 
    {
        $subscriber = $this->subscriberService->findOne($id);

        if($subscriber!=null)
            return $this->successResponse('Subscriber was successfully retrieved', $subscriber, 'subscriber');

        return $this->failureResponse('Subscriber does not exist', 404);
    }



    public function create(SubscriberRequest $request) 
    {
        $inputs = $request->validated();

        if($this->subscriberService->save($inputs)) 
            return $this->successResponseNoData('New subscriber was successfully created', 201);
        
        return $this->failureResponse('There was an error creating subscriber!');
    }


    public function update(Subscriber $subscriber, SubscriberRequest $request) 
    {
        $inputs = $request->validated();

        if($this->subscriberService->save($inputs, $subscriber)) 
            return $this->successResponseNoData('Subscriber was successfully updated');
            
        return failureResponse('There was an error updating subscriber!');
    }


    public function delete(Subscriber $subscriber) 
    {
        if($this->subscriberService->delete($subscriber)) 
            return $this->successResponseNoData('Subscriber was successfully deleted');

        return failureResponse('Subscriber could not be deleted');
    }


}
