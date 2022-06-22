<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Subscriber;
use App\Models\Field;
use Illuminate\Foundation\Testing\DatabaseTransactions;


class SubscribersTest extends TestCase
{

    use DatabaseTransactions;

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
        //create a subscribers
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
                        'subscribers',
                        'pagination'
                    ]
                ]);

        $response->assertStatus(200);

        $result = $this->get_request_response($response);
        $total = $result['data']['pagination']['total'];

        $this->assertEquals($total, $count);
    }

    private function createFields() 
    {
        //create different types of field
        Field::factory()->create();
        Field::factory()->date()->create();
        Field::factory()->number()->create();
        Field::factory()->boolean()->create();
    }


    private function generateEmailWithValidDomain() {
        //get random string
        $str = substr(str_shuffle(str_repeat($x='0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil(5/strlen($x)) )),1,5);
        return $str . '@mailerlite.com';
    }

    public function test_the_api_can_create_new_subscriber() 
    {
        $subscriber = Subscriber::factory()->make([
            'email' => $this->generateEmailWithValidDomain(),
        ])->toArray();

        //create new fields
        $this->createFields();

        $fields = Field::all();

        foreach($fields as $field) {

            $value = $this->faker->text();
            
            switch($field->type) {
                case 'number':
                    $value = 4893;
                    break;
                case 'boolean':
                    $value = false;
                    break;
                case 'date':
                    $value = $this->faker->date();
                    break;
            }
            
            $subscriber[$field->title] = $value;
        }

        $user = User::factory()->create();
        $response = $this->actingAs($user)->post('/api/subscribers/create', $subscriber);

        echo json_encode($response);

        $response->assertStatus(201)->assertJson([
            'status' => true,
        ]);

        $this->assertNotNull(
            Subscriber::where([
                'email' =>$subscriber['email']
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

        $response->assertStatus(422)->assertJson([
            'status' => false,
        ]);

        $this->assertEmpty(
            Subscriber::where('email', $subscriber->email)->first()
        );

    }


    public function test_the_api_can_update_subscriber() {

        //create a subscribers
        $this->seed_db_table();

        $subscriber = Subscriber::first();
        //add the new name for update
        $subscriber->name = $this->faker->name();
        $subscriber->email = $this->generateEmailWithValidDomain();

        $user = User::factory()->create();
        $response = $this->actingAs($user)->put('/api/subscribers/' . $subscriber->id . '/update', $subscriber->toArray());

        echo json_encode($response);

        $response->assertStatus(200)->assertJson([
            'status' => true,
        ]);

        $update_subscriber = Subscriber::find($subscriber->id);

        $this->assertEquals($update_subscriber->name, $subscriber->name);

    }

    public function test_the_api_can_delete_subscriber() {

        //create subscribers
        $this->seed_db_table();

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
        $srch_param = "a";
        $state = 'junk';

        $user = User::factory()->create();
        $response = $this->actingAs($user)->post('/api/subscribers', [
            'search' => $srch_param,
            'state' => $state
        ])->assertJson([
            'status' => true,
        ])->assertJsonStructure([
            "data" => [
                'subscribers',
                'pagination'
            ]
        ]);

        $response->assertStatus(200);

        $count = Subscriber::where('state', $state)
                            ->where(function($query) use ($srch_param) {
                                $query->whereRaw("LOWER(name) LIKE '%" . strtolower($srch_param) . "%' OR LOWER(email) LIKE '%" . strtolower($srch_param) . "%'");
                            })->count();

        $result = $this->get_request_response($response);
        $total = $result['data']['pagination']['total'];

        $this->assertEquals($total, $count);
    }


    public function test_the_api_can_change_subscribers_states() 
    {
        $this->seed_db_table();

        $subscribers = Subscriber::limit(5)->get();
        $ids = $subscribers->pluck('id')->toArray();
        $new_state = 'junk';

        $user = User::factory()->create();
        $response = $this->actingAs($user)->post('/api/subscribers/change-state', [
            'subscribers' => $ids,
            'state' => $new_state
        ]);

        echo json_encode($response);

        $response->assertStatus(200)->assertJson([
            'status' => true,
        ]);

        $first_subscriber = $subscribers->get(0)->fresh();
        $last_subscriber = $subscribers->get($subscribers->count()-1)->fresh();

        $this->assertEquals($first_subscriber->state, $new_state);
        $this->assertEquals($last_subscriber->state, $new_state);
    }


    public function test_the_api_can_catch_invalid_state_when_changing_subscriber_states() 
    {
        $this->seed_db_table();

        $subscribers = Subscriber::limit(5)->get();
        $ids = $subscribers->pluck('id')->toArray();
        $new_state = 'invalid';

        $user = User::factory()->create();
        $response = $this->actingAs($user)->post('/api/subscribers/change-state', [
            'subscribers' => $ids,
            'state' => $new_state
        ]);

        $response->assertStatus(422)->assertJson([
            'status' => false,
        ]);
    }


    public function test_the_api_can_catch_invalid_subscribers_when_changing_subscriber_states() 
    {
        $this->seed_db_table();

        $ids = [3,4,893,3893,'ade'];
        $new_state = 'junk';

        $user = User::factory()->create();
        $response = $this->actingAs($user)->post('/api/subscribers/change-state', [
            'subscribers' => $ids,
            'state' => $new_state
        ]);

        echo json_encode($response);

        $response->assertStatus(422)->assertJson([
            'status' => false,
        ]);
    }
    
}