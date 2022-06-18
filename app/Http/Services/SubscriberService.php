<?php

    namespace App\Http\Services;

    use App\Models\Subscriber;
    use Illuminate\Http\Request;
    use Illuminate\Pagination\LengthAwarePaginator;

    class SubscriberService {

        public function findAll(Request $request) {

            $qry = Subscriber::select('email', 'name', 'state', 'created_at');

            if($request->has('search')) {
                $qry->where('name', 'like', '%'.$request->search.'%');
                $qry->orWhere('email', 'like', '%'.$request->search.'%');
            }

            if($request->has('state')) {
                $qry->where('state', $request->state);
            }

            $records = $qry->paginate($request->per_page ?? 50);

            return $this->extractPaginatedRecords($records);
        }



        private static function extractPaginatedRecords(?LengthAwarePaginator $records): array {

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

    }
