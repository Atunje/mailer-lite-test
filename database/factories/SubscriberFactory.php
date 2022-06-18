<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Subscriber>
 */
class SubscriberFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'state' => 'active',
        ];
    }

    /**
     * Indicate that the state of the subscriber is unsubscribed.
     *
     * @return static
     */
    public function unsubscribed()
    {
        return $this->state(function (array $attributes) {
            return [
                'state' => 'unsubscribed',
            ];
        });
    }

    /**
     * Indicate that the state of the subscriber is junk.
     *
     * @return static
     */
    public function junk()
    {
        return $this->state(function (array $attributes) {
            return [
                'state' => 'junk',
            ];
        });
    }


    /**
     * Indicate that the state of the subscriber is bounced.
     *
     * @return static
     */
    public function bounced()
    {
        return $this->state(function (array $attributes) {
            return [
                'state' => 'bounced',
            ];
        });
    }

    /**
     * Indicate that the state of the subscriber is unconfirmed.
     *
     * @return static
     */
    public function unconfirmed()
    {
        return $this->state(function (array $attributes) {
            return [
                'state' => 'unconfirmed',
            ];
        });
    }
}
