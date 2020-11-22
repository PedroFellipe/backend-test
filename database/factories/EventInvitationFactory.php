<?php

namespace Database\Factories;

use App\Models\Event;
use App\Models\EventInvitation;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class EventInvitationFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = EventInvitation::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => User::factory(),
            'event_id' => Event::factory(),
            'status' => $this->faker->randomElement(array('confirmed', 'rejected', 'awaiting_confirmation')),
        ];
    }
}
