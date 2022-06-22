<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Field;
use Illuminate\Foundation\Testing\DatabaseTransactions;


class FieldsTest extends TestCase
{
    use DatabaseTransactions;

    public function test_the_api_returns_list_of_fields()
    {
        //create a field
        Field::factory()->create();

        //count the number of fields in the db
        $count = Field::count();

        $user = User::factory()->create();
        $response = $this->actingAs($user)->get('/api/fields')
                ->assertJson([
                    'status' => true,
                ])
                ->assertJsonStructure([
                    "data" => [
                        'fields'
                    ]
                ]);

        $response->assertStatus(200);

        $result = $this->get_request_response($response);
        $fields = $result['data']['fields'];

        $this->assertEquals(count($fields), $count);
    }

    public function test_the_api_can_create_new_field() {

        $user = User::factory()->create();
        $response = $this->actingAs($user)->post('/api/fields/create', [
            'title' => 'gender',
            'type' => 'string'
        ]);

        $response->assertStatus(201)->assertJson([
            'status' => true,
        ]);

        $this->assertNotNull(
            Field::where([
                'title' =>'gender'
            ])->get()
        );
                
    }


    public function test_the_api_can_reject_invalid_field_type() {

        $user = User::factory()->create();
        $response = $this->actingAs($user)->post('/api/fields/create', [
            'title' => 'location',
            'type' => 'coordinate'
        ]);

        $response->assertStatus(422)->assertJson([
            'status' => false,
        ]);

        $this->assertEmpty(
            Field::where('title', 'location')->first()
        );

    }


    public function test_the_api_can_update_field() {

        Field::factory()->count(5)->create();

        $field = Field::first();
        $new_title = $this->faker->name;

        $user = User::factory()->create();
        $response = $this->actingAs($user)->put('/api/fields/' . $field->id . '/update', [
            'title' => $new_title,
            'type' => $field->type
        ]);

        echo json_encode($response);

        $response->assertStatus(200)->assertJson([
            'status' => true,
        ]);

        $field = Field::find($field->id);

        $this->assertEquals($field->title, $new_title);

    }
}