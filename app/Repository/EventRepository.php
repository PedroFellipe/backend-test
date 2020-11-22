<?php

namespace App\Repository;

use Auth;
use App\Models\User;
use App\Repository\BaseRepository;
use App\Models\Event;

class EventRepository extends BaseRepository
{

    public function __construct(Event $event)
    {
        $this->model = $event;
    }

    public function myEvents($request){

        //Events that I've created
        $events_i_created = $this->model
            ->where('user_id', Auth::user()->id)
            ->get();

        if($request->scope === 'i_created'){
            return $events_i_created;
        }

        //Events that I will participate
        $events_i_will_participate = $this->model
            ->join('events_invitations', 'event_id', 'events.id')
            ->where('events_invitations.user_id',Auth::user()->id)
            ->where('events_invitations.status', 'confirmed')
            ->get('events.*');

        if($request->scope === 'i_will_participate'){
            return $events_i_will_participate;
        }

        return $events_i_created->merge($events_i_will_participate);

    }
}
