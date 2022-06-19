<?php

    namespace App\Http\Services;

    use App\Models\Subscriber;
    use App\Models\Field;
    use App\Models\FieldValue;
    use Illuminate\Http\Request;
    use Illuminate\Pagination\LengthAwarePaginator;
    use Illuminate\Support\Collection;

    class SubscriberService {

        public function findAll(Request $request): array 
        {
            $qry = Subscriber::select('id', 'email', 'name', 'state', 'created_at')->with('fieldValues');

            if($request->has('state')) {
                $qry->where('state', $request->state);
            }

            if($request->has('search')) {
                $qry->where('name', 'like', '%'.$request->search.'%');
                $qry->orWhere('email', 'like', '%'.$request->search.'%');
            }

            $records = $qry->orderBy('id', 'desc')->paginate($request->per_page ?? 50);

            $this->attachFieldValues($records);

            return $this->extractPaginatedRecords($records);
        }


        public function findOne(int $id): Subscriber 
        {
            $subscriber = Subscriber::find($id);

            if($subscriber != null) {
                $this->getFieldValues($subscriber, Field::all());
            }

            return $subscriber;
        }


        /**
         * Get the fields and attach it to each record
         */
        private function attachFieldValues($records) {

            //get the fields
            $fields = Field::all();

            foreach($records as $subscriber) {
                
                $this->getFieldValues($subscriber, $fields);

            };

        }


        private function getFieldValues(Subscriber $subscriber, Collection $fields)
        {
            //loop thru the fields and attach to the subscriber record
            foreach($fields as $field) {

                //get the title of the field
                $title = $field->title;

                //get the fieldvalue, default to null if it does not exist
                $fieldValue = $this->getFieldValue($subscriber->fieldValues, $field);

                //set the field on the subscriber
                $subscriber->$title = $fieldValue->value ?? null;
                
            }
            
            //remove the fieldValue relationship from the subscriber record
            unset($subscriber->fieldValues);
        }


        private function getFieldValue($fieldValues, $field)
        {
            foreach($fieldValues as $fieldValue) {
                if($fieldValue->field_id == $field->id) {
                    return $fieldValue;
                }
            }

            return null;
        }



        private static function extractPaginatedRecords(?LengthAwarePaginator $records): array 
        {
            $data = [];

            if($records != null) {

                $records = $records->toArray();

                $data['subscribers'] = $records['data'];

                $data['pagination'] = array_intersect_key($records,
                    array_flip(
                        array('per_page', 'current_page', 'from', 'to', 'total')
                    )
                );

            }

            return $data;
        }


        
        public function save(array $inputs, ?Subscriber $subscriber = null) 
        {
            if($subscriber == null) {
                $subscriber = Subscriber::create($inputs);
            } else {
                $subscriber->update($inputs);
            }

            //re-hydrate the subscriber to fill in all fields from the db
            $subscriber->refresh();

            if($subscriber != null) {

                foreach($inputs as $field_name => $value) {

                    //if subscriber does not have attribute field_name, save the input as a fieldvalue
                    if(!isset($subscriber->$field_name)) {

                        $this->saveField($subscriber, $field_name, $value);

                    }
                    
                }

                return true;
            }

            return false;
        }


        private function saveField(Subscriber $subscriber, string $title, string $value) 
        {
            //get the field
            $field = Field::where('title', $title)->first();

            if($field!=null) {

                $params = ['field_id' => $field->id, 'subscriber_id' => $subscriber->id];

                //get existing record
                $existing = FieldValue::where($params)->first();

                //create or update the fieldvalue
                $field_value = $existing == null ? new FieldValue($params) : $existing;
                $field_value->value = $value;
                $field_value->save();
            }
        }

    }
