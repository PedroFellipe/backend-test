<?php

namespace Tests\Feature;

use Auth;
use App\Models\Event;
use App\Models\User;


use Tests\TestCase;

class EventTest extends TestCase
{

    protected $user;

    public function setUp(): void
    {
        parent::setUp();
        $user = User::factory()->create();
        $this->user = $user;

        $this->actingAs($user);
    }

    /**
     * Teste Get Events
     *
     * @return void
     */
    public function testGetEvents()
    {

        $response = $this->get('/api/event');
        $response->assertStatus(200)
            ->assertJsonCount(0);

        $events = Event::factory()->count(3)->create();
        $response = $this->get('/api/event');

        $response->assertStatus(200)
            ->assertJsonCount(3);

        //Testing pagination
        $events = Event::factory()->count(15)->create();
        $response = $this->get('/api/event');

        $response->assertStatus(200)
            ->assertJsonCount(10);
    }

    /**
     * Teste Get Events
     *
     * @return void
     */
    public function testGetEventNotFound()
    {

        $response = $this->get(route('event.show', 10));
        $response->assertStatus(404)
            ->assertExactJson(['message' => 'event not found!']);

    }


    /**
     * Teste Get Events
     *
     * @return void
     */
    public function testGetEventOk()
    {

        $event = Event::factory()->create();
        $response = $this->get(route('event.show', $event->id));

        $response->assertStatus(200);
    }


    /**
     * Test Post Events
     *
     * @return void
     */
    public function testPostEventsOk()
    {

        $response = $this->post(route('event.store'), Event::factory()->make()->toArray());
        $response->assertStatus(201);

        // Test validation with no data in payload
        $response = $this->post(route('event.store'), []);
        $response->assertStatus(422);
    }

    /**
     * Test Post Events
     *
     * @return void
     */
    public function testPostEventsValidationError()
    {

        // Test validation with no data in payload
        $response = $this->post(route('event.store'), []);
        $response->assertStatus(422);
    }


    /**
     * Test PUT Events
     *
     * @return void
     */
    public function testPutEventsNotFound()
    {

        $response = $this->put(route('event.update', 10), Event::factory()->make()->toArray());

        $response->assertStatus(404)
            ->assertExactJson(['message' => 'Event not found!']);


    }

    /**
     * Test PUT Events
     *
     * @return void
     */
    public function testPutEventsOk()
    {

        $event = Event::factory()->create(['user_id' => $this->user->id]);
        $response = $this->put(route('event.update', $event->id), $event->toArray());
        $response->assertStatus(200);


    }

    /**
     * Test PUT Events
     *
     * @return void
     */
    public function testPutEventsCannotEdit()
    {

        $user = User::factory()->create();
        $event = Event::factory()->create(['user_id' => $user->id]);
        $response = $this->put(route('event.update', $event->id), $event->toArray());
        $response->assertStatus(400)->assertExactJson(['message' => 'You cannot edit this event because you are not the creator!']);
    }


    /**
     * Test Post Events
     *
     * @return void
     */
    public function testDeleteEventNotFound()
    {
        $response = $this->delete(route('event.destroy', 10));
        $response->assertStatus(404)
            ->assertExactJson(['message' => 'Event not found!']);


    }

    /**
     * Test Post Events
     *
     * @return void
     */
    public function testDeleteEventOk()
    {
        $event = Event::factory()->create(['user_id' => $this->user->id]);
        $response = $this->delete(route('event.destroy', $event->id));
        $response->assertStatus(200);

    }

    /**
     * Test Post Events
     *
     * @return void
     */
    public function testEventCanceledDelete()
    {

        $event = Event::factory()->create(['user_id' => $this->user->id]);
        $response = $this->delete(route('event.destroy', $event->id));
        $response = $this->delete(route('event.destroy', $event->id));
        $response->assertStatus(400)->assertExactJson(['message' => 'This event has already been canceled']);

    }

    /**
     * Test Post Events
     *
     * @return void
     */
    public function testDeleteEventNotCreator()
    {
        $user = User::factory()->create();
        $event = Event::factory()->create(['user_id' => $user->id]);
        $response = $this->delete(route('event.destroy', $event->id));
        $response->assertStatus(400)->assertExactJson(['message' => 'You cannot cancel this event because you are not the creator']);
    }
}
