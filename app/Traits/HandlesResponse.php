<?php

    namespace App\Traits;

    use Exception;
    use Illuminate\Database\Eloquent\Model;
    use Illuminate\Http\JsonResponse;

    trait HandlesResponse {

        private array $response = [];

        /**
         * @param string|null $message
         * @param object|array $data
         * @param string $data_key
         * @return JsonResponse
         */
        protected function successResponse(?string $message, object|array $data=[], string $data_key="data", int $status_code=200): JsonResponse {

            $response = [];

            $response['status'] = true;

            if($message!=null) $response['message'] = $message;

            $response['data'] = $data;

            if(!empty($data)) {

                if(is_array($data)) {

                    $response['data'] = $data;

                } else {

                    $response['data'] = [$data_key=>$data];

                }
            }

            return response()->json($response, $status_code);

        }


        /**
         * @param string $message_option1
         * @param string|null $message_option2
         * @return JsonResponse
         */
        protected function successResponseNoData(string $message, $status_code=200): JsonResponse {

            return response()->json(['status' => true, 'message' => $message], $status_code);

        }

        /**
         * @param string $message
         * @param int $status_code
         * @return JsonResponse
         */
        protected function failureResponse(string $message, int $status_code=200): JsonResponse {

            return response()->json(['status' => false, 'message' => $message], $status_code);

        }




    }
