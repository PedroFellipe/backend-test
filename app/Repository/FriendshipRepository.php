<?php

namespace App\Repository;

use App\Mail\InviteFriend;
use Auth;
use App\Models\User;
use App\Repository\BaseRepository;
use App\Models\Friendship;
use Illuminate\Support\Facades\Mail;

class FriendshipRepository extends BaseRepository
{

    public function __construct(Friendship $friendship)
    {
        $this->model = $friendship;
    }

    /**
     * Invites a friendship to a user
     * @param $email
     * @return mixed
     */
    public function invite($email)
    {
        $user = User::where('email', $email)->first();

        if ($user) {


            return $this->create([
                'first_user_id' => Auth::user()->id,
                'second_user_id' => $user->id,
                'email' => $email,
                'confirmed' => 0
            ]);
        }

        Mail::send(new InviteFriend($email));
        return $this->create([
            'first_user_id' => Auth::user()->id,
            'email' => $email,
            'confirmed' => 0
        ]);
    }

    /**
     * Verify if the logged user is already friend of the invited user
     * @param $email
     * @return mixed
     */
    public function alreadyFriends($email)
    {
        $user_invited = User::where('email', $email)->first();

        if (!$user_invited) {
            return false;
        }

        if (!Auth::user()->friendsThisUserAsked()->where('users.email', $email)->count() &&
            !Auth::user()->friendsThisUserWasAsked()->where('users.email', $email)->count()) {
            return false;
        }

        return true;
    }

    /**
     * Verify if the logged user already invited the user
     * @param $email
     * @return mixed
     */
    public function alreadyInvited($email)
    {

        $friendship_request = Friendship::where('email', $email)
            ->where('first_user_id', '=', Auth::user()->id)
            ->where('confirmed', 0)
            ->first();

        if ($friendship_request) {

            return true;
        }

        return false;
    }

    /**
     * Verify if the logged user already is invitated by the user
     * @param $email
     * @return mixed
     */
    public function alreadyInvitated($email)
    {

        $user_invited = User::where('email', $email)->first();

        if (!$user_invited) {
            return false;
        }

        $friendship_request = Friendship::where('first_user_id', '=', $user_invited->id)
            ->where('second_user_id', Auth::user()->id)
            ->where('confirmed', 0)
            ->first();

        if ($friendship_request) {
            return true;
        }

        return false;
    }

    /**
     * Get friendship requests
     * @return mixed
     */
    public function friendshipRequests()
    {
        return $this->model
            ->where('email', Auth::user()->email)
            ->where('confirmed', 0)
            ->get();
    }

    /**
     * Get friendship list
     * @return mixed
     */
    public function friendshipList()
    {
        $user = Auth::user();
        $friends = $user->friendsThisUserAsked;

        return $friends->merge($user->friendsThisUserWasAsked);
    }

    /**
     * Verify if a list of users are your friends
     * @param array $friends_id
     * @return mixed
     */
    public function verifyFriends(array $friends_id)
    {

        foreach ($friends_id as $friend_id) {

            $user = User::find($friend_id);

            if(!$user){
                return true;
            }

            if (!$this->alreadyFriends($user->email)) {
                return true;
            }
        }

        return false;
    }
}
