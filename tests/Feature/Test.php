<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;

class Test extends TestCase
{

    private String $test_email = "nobelatunje001@gmail.com";
    private String $test_password = "Password123.";

    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_the_api_returns_a_successful_response()
    {
        $response = $this->get('/api');
        $response->assertStatus(200);
    }


    public function test_the_api_can_register_new_user() {

        $response = $this->json('POST', '/api/auth/register', [
            'name' => $this->faker->name,
            'email'=>$this->test_email,
            'password'=>$this->test_password
        ]);

        $response->assertStatus(201)->assertJson([
            'status' => true,
        ]);

        $this->assertNotNull(
            User::where([
                'email' => $this->test_email
            ])->get()
        );

    }


    public function test_the_api_can_login_user() {

        $response = $this->json('POST', '/api/auth/login', [
            'email'=>$this->test_email,
            'password'=>$this->test_password
        ]);

        $response->assertStatus(200)->assertJson([
            'status' => true,
        ]);

        //get the access token
        $result = $this->get_request_response($response);
        $this->headers['Authorization'] = "Bearer " . $result['data']['access_token'];

        //validate that the token works
        $response = $this->get('/api/user', $this->headers);
        $response->assertStatus(200)->assertJson([
            'status' => true,
        ]);

    }

    public function test_the_api_can_log_user_out() {

        //login
        $response = $this->json('POST', '/api/auth/login', [
            'email'=>$this->test_email,
            'password'=>$this->test_password
        ]);

        //get the access token
        $result = $this->get_request_response($response);
        $this->headers['Authorization'] = "Bearer " . $result['data']['access_token'];

        $response = $this->get('/api/auth/logout', $this->headers);
        $response->assertStatus(200)->assertJson([
            'status' => true,
        ]);
    }

}
