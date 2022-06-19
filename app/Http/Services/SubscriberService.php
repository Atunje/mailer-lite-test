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
            $records = $this->getSubscribers($request);

            $this->attachFieldValuesToSubscribers($records);

            return $request->per_page == -1 ? $records : $this->simplifyPagination($records);
        }


        private function getSubscribers(Request $request): LengthAwarePaginator|Collection {

            $qry = Subscriber::select('id', 'email', 'name', 'state', 'created_at')->with('fieldValues');

            if($request->has('state')) {
                $qry->where('state', $request->state);
            }

            if($request->has('search')) {
                $qry->where('name', 'like', '%'.$request->search.'%');
                $qry->orWhere('email', 'like', '%'.$request->search.'%');
            }

            $qry->orderBy('id', 'desc');

            return $request->per_page == -1 ? $qry->get() : $qry->paginate($request->per_page ?? 50);

        }


        /**
         * Attach Field Values to Subscribers
         * 
         * Attach fields and available values to the subscribers supplied
         */
        private function attachFieldValuesToSubscribers(Collection|LengthAwarePaginator $subscribers) {

            //get the fields
            $fields = Field::all();

            foreach($subscribers as $subscriber) {
                
                $this->attachFieldValues($subscriber, $fields);

            };

        }


        private function attachFieldValues(Subscriber $subscriber, ?Collection $fields = null)
        {
            $fields = $fields == null ? Field::all() : $fields;

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


        /**
         * Get the field value for the given field
         */
        private function getFieldValue(Collection $fieldValues, Field $field): ?FieldValue
        {
            foreach($fieldValues as $fieldValue) {
                if($fieldValue->field_id == $field->id) {
                    return $fieldValue;
                }
            }

            return null;
        }


        /**
         * Separate subscribers from pagination information
         */
        private static function simplifyPagination(?LengthAwarePaginator $records): array 
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


        /**
         * Find One
         * 
         * Get subscriber 
         * Attach all fields and available values 
         */
        public function findOne(int $id): ?Subscriber 
        {
            $subscriber = Subscriber::find($id);

            if($subscriber != null) {
                $this->attachFieldValues($subscriber, Field::all());
            }

            return $subscriber;
        }


        /**
         * Save
         * 
         * Create or Update subscriber
         * Also creates field values
         */
        public function save(array $inputs, ?Subscriber $subscriber = null) 
        {
            //save the subscriber
            $subscriber = $this->saveSubscriber($inputs, $subscriber);

            //re-hydrate the subscriber to fill in all fields from the db
            $subscriber->refresh();

            if($subscriber != null) {

                foreach($inputs as $field_name => $value) {
                    $this->saveField($subscriber, $field_name, $value);
                }

                return true;
            }

            return false;
        }


        /**
         * Save only the subscriber without field values
         * 
         * Creates new one if subscriber specified is null
         * Updates if the subscriber already exists
         */
        private function saveSubscriber(array $inputs, ?Subscriber $subscriber) {
            
            if($subscriber == null) {
                $subscriber = Subscriber::create($inputs);
            } else {
                $subscriber->update($inputs);
            }

            return $subscriber;

        }


        /**
         * Save fieldvalue for the given subscriber
         */
        private function saveField(Subscriber $subscriber, string $field_name, string $value) 
        {
            //get the field
            $field = Field::where('title', $field_name)->first();

            //proceed if the attribute does not exist on the subscriber and the field exists
            if(!isset($subscriber->$field_name) && $field!=null) {

                $params = ['field_id' => $field->id, 'subscriber_id' => $subscriber->id];

                //get existing record
                $existing = FieldValue::where($params)->first();

                //create or update the fieldvalue
                $field_value = $existing == null ? new FieldValue($params) : $existing;
                $field_value->value = $value;
                $field_value->save();
            }
        }


        /**
         * Delete
         * 
         * Deletes a subscriber
         */
        public function delete(Subscriber $subscriber): bool {
            return $subscriber->delete();
        }

    }
