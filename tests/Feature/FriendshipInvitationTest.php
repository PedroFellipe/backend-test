<?php

namespace Tests\Feature;

use App\Models\Friendship;
use Auth;
use App\Models\Event;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Artisan;

use Tests\TestCase;

class FriendshipInvitationTest extends TestCase
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
    public function testGetFriendshipInvitation()
    {

        $response = $this->get(route('friendship_invitation.index'));
        $response->assertStatus(200)
            ->assertJsonCount(0);

    }

    /**
     * Test Post FriendShip
     *
     * @return void
     */
    public function testPostFriendshipInvitationValidationError()
    {


        // Test validation with no data in payload
        $response = $this->post(route('friendship_invitation.store'), []);
        $response->assertStatus(422);
    }

    /**
     * Test Post Friendship
     *
     * @return void
     */
    public function testPostFriendshipAlreadyInvited()
    {

        $user = User::factory()->create();

        $friendship = Friendship::factory()->create([
            'first_user_id' => $this->user->id,
            'second_user_id' => $user->id,
            'email' => $user->email,
            'confirmed' => 0
        ]);

        // Test validation with no data in payload
        $response = $this->post(route('friendship_invitation.store'), ['email' => $friendship->email]);
        $response->assertStatus(400)->assertExactJson(['message' => 'you\'ve already made a friend invitation to that person!']);
    }

    /**
     * Test Post Friendship
     *
     * @return void
     */
    public function testPostFriendshipAlreadyInvitatedMe()
    {

        $user = User::factory()->create();

        $friendship = Friendship::factory()->create([
            'first_user_id' => $user->id,
            'second_user_id' => $this->user->id,
            'email' => $this->user->email,
            'confirmed' => 0
        ]);

        // Test validation with no data in payload
        $response = $this->post(route('friendship_invitation.store'), ['email' => $user->email]);
        $response->assertStatus(400)->assertExactJson(['message' => 'that person has already invited you to be your friend!']);
    }

    /**
     * Test Post Friendship
     *
     * @return void
     */
    public function testPostFriendshipAlreadyFriends()
    {

        $user = User::factory()->create();

        $friendship = Friendship::factory()->create([
            'first_user_id' => $user->id,
            'second_user_id' => $this->user->id,
            'email' => $this->user->email,
            'confirmed' => 1
        ]);

        // Test validation with no data in payload
        $response = $this->post(route('friendship_invitation.store'), ['email' => $user->email]);
        $response->assertStatus(400)->assertExactJson(['message' => 'you\'re already friends!']);
    }

    /**
     * Test Post Friendship
     *
     * @return void
     */
    public function testPostFriendshipInviteYourself()
    {

        $user = User::factory()->create();

        $friendship = Friendship::factory()->create([
            'first_user_id' => $user->id,
            'second_user_id' => $this->user->id,
            'email' => $this->user->email,
            'confirmed' => 1
        ]);

        // Test validation with no data in payload
        $response = $this->post(route('friendship_invitation.store'), ['email' => $this->user->email]);
        $response->assertStatus(400)->assertExactJson(['message' => 'you can\'t invite yourself!']);
    }

    /**
     * Test Post Friendship
     *
     * @return void
     */
    public function testPostFriendshipOk()
    {

        $user = User::factory()->create();

        // Test validation with no data in payload
        $response = $this->post(route('friendship_invitation.store'), ['email' => $user->email]);
        $response->assertStatus(201);
    }

    /**
     * Test Post Friendship
     *
     * @return void
     */
    public function testPutFriendshipNotFound()
    {

        $user = User::factory()->create();

        // Test validation with no data in payload
        $response = $this->put(route('friendship_invitation.update', 100));
        $response->assertStatus(404)->assertExactJson(['message' => 'Friendship request not found!!']);
    }

    /**
     * Test Post Friendship
     *
     * @return void
     */
    public function testPutFriendshipAlreadyAccepted()
    {

        $user = User::factory()->create();

        $friendship = Friendship::factory()->create([
            'first_user_id' => $user->id,
            'second_user_id' => $this->user->id,
            'email' => $this->user->email,
            'confirmed' => 1
        ]);

        // Test validation with no data in payload
        $response = $this->put(route('friendship_invitation.update', $friendship->id));
        $response->assertStatus(400)->assertExactJson(['message' => 'This friend request has already been accepted !!']);
    }

    /**
     * Test Post Friendship
     *
     * @return void
     */
    public function testPutFriendshipOk()
    {

        $user = User::factory()->create();

        $friendship = Friendship::factory()->create([
            'first_user_id' => $user->id,
            'second_user_id' => $this->user->id,
            'email' => $this->user->email,
            'confirmed' => 0
        ]);

        // Test validation with no data in payload
        $response = $this->put(route('friendship_invitation.update', $friendship->id));
        $response->assertStatus(200);
    }

    /**
     * Test Post Friendship
     *
     * @return void
     */
    public function testDeleteFriendshipNotFound()
    {
        // Test validation with no data in payload
        $response = $this->delete(route('friendship_invitation.destroy', 100));
        $response->assertStatus(404)->assertExactJson(['message' => 'Friendship request not found!!']);
    }


}
