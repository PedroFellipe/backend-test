<?php

namespace Tests\Feature;

use App\Models\EventInvitation;
use App\Models\Friendship;
use Auth;
use App\Models\Event;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Artisan;

use Tests\TestCase;

class EventInvitationTest extends TestCase
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
     * Test Get EventInvitation
     *
     * @return void
     */
    public function testGetEventInvitationEmpty()
    {

        $response = $this->get(route('user.invitations'));
        $response->assertStatus(200)
            ->assertJsonCount(0);

    }

    /**
     * Teste Get EventInvitation
     *
     * @return void
     */
    public function testGetEventInvitationWithData()
    {

        $friendship = EventInvitation::factory()->create([
            'user_id' => $this->user->id,
        ]);

        $response = $this->get(route('user.invitations'));
        $response->assertStatus(200)
            ->assertJsonCount(1);

    }

    /**
     * Test Post EventInvitation
     *
     * @return void
     */
    public function testPostEventInvitationErrorYouHaveNotCreatedThisEvent()
    {

        $event = Event::factory()->create();

        // Test validation with no data in payload
        $response = $this->post(route('event.invitation.inviteEvent', $event->id), ['type' => 'all']);
        $response->assertStatus(400)->assertExactJson(['message' => 'You can only send invitations to events that you have created!']);
    }

    /**
     * Test Post EventInvitation
     *
     * @return void
     */
    public function testPostEventInvitationErrorYouCanOnlyInviteYourFriends()
    {

        $event = Event::factory()->create(['user_id' => $this->user->id]);

        // Test validation with no data in payload
        $response = $this->post(route('event.invitation.inviteEvent', $event->id), ['users_id' => [10, 11, 12]]);
        $response->assertStatus(400)->assertExactJson(['message' => 'You can only invite your friends!']);
    }

    /**
     * Test Post EventInvitation
     *
     * @return void
     */
    public function testPostEventInvitationErrorCanceledEvent()
    {

        $event = Event::factory()->create(['user_id' => $this->user->id, 'canceled' => 1]);

        // Test validation with no data in payload
        $response = $this->post(route('event.invitation.inviteEvent', $event->id), ['users_id' => [10, 11, 12]]);
        $response->assertStatus(400)->assertExactJson(['message' => 'You can not invite friends for a canceled event!']);
    }

    /**
     * Test Post EventInvitation
     *
     * @return void
     */
    public function testPostEventInvitationOk()
    {

        $user = User::factory()->create();

        $friendship = Friendship::factory()->create([
            'first_user_id' => $user->id,
            'second_user_id' => $this->user->id,
            'email' => $this->user->email,
            'confirmed' => 1
        ]);
        $event = Event::factory()->create(['user_id' => $this->user->id, 'canceled' => 0]);

        // Test validation with no data in payload
        $response = $this->post(route('event.invitation.inviteEvent', $event->id), ['users_id' => [$user->id]]);
        $response->assertStatus(200)->assertExactJson(['message' => 'Invitations sent successfully']);
    }

    /**
     * Test Put EventInvitation
     *
     * @return void
     */
    public function testPutEventInvitationNotFound()
    {
        // Test validation with no data in payload
        $response = $this->put(route('event.invitation.updateEventInvitation', [100, 100]), ['status' => 'confirmed']);
        $response->assertStatus(404)->assertExactJson(['message' => 'Event invitation not found!']);
    }

    /**
     * Test Put EventInvitation
     *
     * @return void
     */
    public function testPutEventInvitationEventCanceled()
    {
        $user = User::factory()->create();

        $friendship = Friendship::factory()->create([
            'first_user_id' => $user->id,
            'second_user_id' => $this->user->id,
            'email' => $this->user->email,
            'confirmed' => 1
        ]);
        $event = Event::factory()->create(['user_id' => $this->user->id, 'canceled' => 1]);

        $event_envitation = EventInvitation::factory()->create([
            'user_id' => $this->user->id,
            'event_id' => $event->id,
            'status' => 'awaiting_confirmation'
        ]);

        $response = $this->put(route('event.invitation.updateEventInvitation', [$event->id, $event_envitation->id]), ['status' => 'confirmed']);
        $response->assertStatus(400)->assertExactJson(['message' => 'This event was canceled!']);
    }

    /**
     * Test Put EventInvitation
     *
     * @return void
     */
    public function testPutEventInvitationEventAlreadyHappened()
    {
        $user = User::factory()->create();

        $friendship = Friendship::factory()->create([
            'first_user_id' => $user->id,
            'second_user_id' => $this->user->id,
            'email' => $this->user->email,
            'confirmed' => 1
        ]);

        $date = $this->faker->dateTimeBetween('-2 years', '-1 days');
        $event = Event::factory()->create(['user_id' => $this->user->id, 'canceled' => 0, 'date' => $date->format('Y-m-d')]);

        $event_envitation = EventInvitation::factory()->create([
            'user_id' => $this->user->id,
            'event_id' => $event->id,
            'status' => 'awaiting_confirmation'
        ]);

        $response = $this->put(route('event.invitation.updateEventInvitation', [$event->id, $event_envitation->id]), ['status' => 'confirmed']);
        $response->assertStatus(400)->assertExactJson(['message' => 'you cannot change the status of the invitation, because this event has already happened!']);
    }

    /**
     * Test Put EventInvitation
     *
     * @return void
     */
    public function testPutEventInvitationOk()
    {
        $user = User::factory()->create();

        $friendship = Friendship::factory()->create([
            'first_user_id' => $user->id,
            'second_user_id' => $this->user->id,
            'email' => $this->user->email,
            'confirmed' => 1
        ]);

        $event = Event::factory()->create(['user_id' => $this->user->id, 'canceled' => 0]);

        $event_envitation = EventInvitation::factory()->create([
            'user_id' => $this->user->id,
            'event_id' => $event->id,
            'status' => 'awaiting_confirmation'
        ]);

        $response = $this->put(route('event.invitation.updateEventInvitation', [$event->id, $event_envitation->id]), ['status' => 'confirmed']);
        $response->assertStatus(200);
    }
}
