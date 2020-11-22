<?php

namespace App\Repository;

use App\Models\EventInvitation;
use App\Models\Friendship;
use App\Models\User;
use Auth;

class EventInvitationRepository extends BaseRepository
{
    protected $friendshipRepository;

    public function __construct(EventInvitation $event_invitation, FriendshipRepository $friendship)
    {
        $this->model = $event_invitation;
        $this->friendshipRepository = $friendship;

    }

    /**
     * Verify if a list of users are your friends
     * @param $request
     * @param $data
     * @param $event
     * @return mixed
     */
    public function inviteFriends($request, $data, $event)
    {

        //all friends
        if ($request->type === 'all') {
            $friends = $this->friendshipRepository->friendshipList();
            foreach ($friends as $friend) {

                if (!EventInvitation::where('user_id', $friend->id)->where('event_id', $event->id)->first()) {
                    $this->create(['user_id' => $friend->id, 'event_id' => $event->id, 'status' => 'awaiting_confirmation']);
                }
            }
            return;
        }

        //some friends
        foreach ($data['users_id'] as $user_id) {
            if (!EventInvitation::where('user_id', $user_id)->where('event_id', $event->id)->first()) {
                $this->create(['user_id' => $user_id, 'event_id' => $event->id, 'status' => 'awaiting_confirmation']);
            }
        }
        return;
    }

}
