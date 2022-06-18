<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Subscriber;

class SubscribersTest extends TestCase
{

    private function seed_db_table() 
    {
        //seed the db table with different subscriber states
        Subscriber::factory()->count(5)->create();
        Subscriber::factory()->count(5)->unsubscribed()->create();
        Subscriber::factory()->count(5)->junk()->create();
        Subscriber::factory()->count(5)->bounced()->create();
        Subscriber::factory()->count(5)->unconfirmed()->create();
    }

    public function test_the_api_returns_list_of_subscribers()
    {
        //create a subscriber
        $this->seed_db_table();

        //count the number of subscribers in the db
        $count = Subscriber::count();

        $user = User::factory()->create();
        $response = $this->actingAs($user)->get('/api/subscribers')
                ->assertJson([
                    'status' => true,
                ])
                ->assertJsonStructure([
                    "data" => [
                        'subscribers'
                    ]
                ]);

        $response->assertStatus(200);

        $result = $this->get_request_response($response);
        $subscribers = $result['data']['pagination']['total'];

        $this->assertEquals(count($subscribers), $count);
    }

    public function test_the_api_can_create_new_subscriber() 
    {
        $subscriber = Subscriber::factory()->make();

        $user = User::factory()->create();
        $response = $this->actingAs($user)->post('/api/subscribers/create', $subscriber->toArray());

        $response->assertStatus(201)->assertJson([
            'status' => true,
        ]);

        $this->assertNotNull(
            Subscriber::where([
                'email' =>$subscriber->email
            ])->get()
        );
                
    }


    public function test_the_api_can_reject_invalid_subscriber_state() 
    {
        $subscriber = Subscriber::factory()->make([
            'state' => 'invalid_type'
        ]);

        $user = User::factory()->create();
        $response = $this->actingAs($user)->post('/api/subscribers/create', $subscriber->toArray());

        $response->assertStatus(400)->assertJson([
            'status' => false,
        ]);

        $this->assertEmpty(
            Subscriber::where('email', $subscriber->email)->first()
        );

    }


    public function test_the_api_can_update_subscriber() {

        $subscriber = Subscriber::first();
        //add the new name for update
        $subscriber->name = $this->faker->name();

        $user = User::factory()->create();
        $response = $this->actingAs($user)->put('/api/subscribers/' . $subscriber->id . '/update', $subscriber->toArray());

        $response->assertStatus(200)->assertJson([
            'status' => true,
        ]);

        $update_subscriber = Subscriber::where('email', $subscriber->email)->first();

        $this->assertEquals($update_subscriber->name, $subscriber->name);

    }

    public function test_the_api_can_delete_subscriber() {

        $subscriber = Subscriber::first();

        $user = User::factory()->create();
        $response = $this->actingAs($user)->delete('/api/subscribers/' . $subscriber->id . '/delete');

        $response->assertStatus(200)->assertJson([
            'status' => true,
        ]);

        $this->assertEmpty(
            Subscriber::where('email', $subscriber->email)->first()
        );

    }


    public function test_the_api_can_search_for_subscribers_by_search_field_and_state() 
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->post('/api/subscribers', [
            'search' => 'ade',
            'state' => 'active'
        ])->assertJson([
            'status' => true,
        ])->assertJsonStructure([
            "data" => [
                'subscribers'
            ]
        ]);

        $response->assertStatus(200);
    }
}