<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;


class AuthTest extends TestCase
{
    use DatabaseTransactions;

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

        $name = $this->faker->name;
        $email = $this->faker->email;
        $password = $this->faker->password;

        $response = $this->json('POST', '/api/auth/register', [
            'name' => $name,
            'email'=> $email,
            'password'=> $password
        ]);

        $response->assertStatus(201)->assertJson([
            'status' => true,
        ]);

        echo json_encode($response);

        $this->assertNotNull(
            User::where([
                'email' => $email
            ])->get()
        );

    }


    public function test_the_api_can_login_user() {

        $name = $this->faker->name;
        $email = $this->faker->email;
        $password = $this->faker->password;

        $response = $this->json('POST', '/api/auth/register', [
            'name' => $name,
            'email'=> $email,
            'password'=> $password
        ]);

        $response = $this->json('POST', '/api/auth/login', [
            'email'=>$email,
            'password'=>$password
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

        $name = $this->faker->name;
        $email = $this->faker->email;
        $password = $this->faker->password;

        $response = $this->json('POST', '/api/auth/register', [
            'name' => $name,
            'email'=> $email,
            'password'=> $password
        ]);

        //login
        $response = $this->json('POST', '/api/auth/login', [
            'email'=>$email,
            'password'=>$password
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
