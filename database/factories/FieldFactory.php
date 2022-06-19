<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Field>
 */
class FieldFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'title' => $this->faker->randomElement(['address', 'location', 'company', 'phone_number', 'next_of_kin', 'city', 'school']),
            'type' => 'string',
        ];
    }

    /**
     * Indicate that the type of the field as date.
     *
     * @return static
     */
    public function date()
    {
        return $this->state(function (array $attributes) {
            return [
                'title' => $this->faker->randomElement(['date_of_birth', 'arrival_date', 'departure_date', 'registration_date', 'next_transaction_date', 'date', 'entry_date']),
                'type' => 'date',
            ];
        });
    }

    /**
     * Indicate that the type of the field as number.
     *
     * @return static
     */
    public function number()
    {
        return $this->state(function (array $attributes) {
            return [
                'title' => $this->faker->randomElement(['amount', 'no_of_children', 'years_of_experience', 'worth', 'cars', 'houses']),
                'type' => 'number',
            ];
        });
    }

    /**
     * Indicate that the type of the field is boolean
     *
     * @return static
     */
    public function boolean()
    {
        
        return $this->state(function (array $attributes) {
            return [
                'title' => $this->faker->randomElement(['verified', 'suspended', 'graded', 'employed', 'paid']),
                'type' => 'boolean',
            ];
        });
    }
}
