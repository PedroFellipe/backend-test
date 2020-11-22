<?php

namespace Database\Factories;

use App\Models\Event;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class EventFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Event::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {

        $date = $this->faker->dateTimeBetween('+0 days', '+2 years');

        return [
            'user_id' => User::factory(),
            'name' => $this->faker->word,
            'description' => $this->faker->text,
            'date' => $date->format('Y-m-d'),
            'time' => $this->faker->date('h:i'),
            'place' => $this->faker->city,
            'canceled' => 0
        ];
    }
}
