<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected $faker;

    protected $headers;

    public function __construct(?string $name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);

        $this->faker = \Faker\Factory::create();

        $this->headers = [
            'Accept' => 'application/json'
        ];
    }


    protected function get_request_response($response) {
        $result = $response->decodeResponseJson();
        return json_decode($result->json, true);
    }

}
