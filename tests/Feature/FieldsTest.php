<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Field;

class FieldsTest extends TestCase
{

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

        $response->assertStatus(400)->assertJson([
            'status' => false,
        ]);

        $this->assertEmpty(
            Field::where('title', 'location')->first()
        );

    }


    public function test_the_api_can_update_field() {

        $field = Field::first();

        $user = User::factory()->create();
        $response = $this->actingAs($user)->post('/api/fields/' . $field->id . '/update', [
            'title' => $field->title,
            'type' => 'boolean'
        ]);

        $response->assertStatus(200)->assertJson([
            'status' => true,
        ]);

        $field = Field::where('title', $field->title)->first();

        $this->assertEquals($field->type, 'boolean');

    }
}