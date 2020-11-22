<?php

namespace App\Http\Controllers;

use App\Http\Resources\EventInvitationResource;
use App\Http\Requests\EventInvitationRequest;
use App\Repository\EventInvitationRepository;
use App\Repository\FriendshipRepository;
use App\Http\Resources\EventResource;
use App\Repository\EventRepository;
use App\Http\Requests\EventRequest;
use Illuminate\Routing\Controller;
use Illuminate\Http\JsonResponse;
use App\Responses\ErrorResponse;
use Illuminate\Http\Request;
use Exception;
use Auth;
use DB;

class EventController extends Controller
{

    protected $eventRepository;
    protected $friendshipRepository;
    protected $eventInvitationRepository;

    public function __construct(EventRepository $event, EventInvitationRepository $event_invitation, FriendshipRepository $friendship)
    {
        $this->eventRepository = $event;
        $this->eventInvitationRepository = $event_invitation;
        $this->friendshipRepository = $friendship;
    }

    /**
     * Lists all the events
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request)
    {
        $events = $this->eventRepository->paginateRequest($request->all());

        return new JsonResponse(EventResource::collection($events), JsonResponse::HTTP_OK);
    }

    /**
     * Creates a event
     * @param EventRequest $request
     * @return JsonResponse
     * @throws Exception
     */
    public function store(EventRequest $request)
    {
        $data = $request->validated();

        try {
            DB::beginTransaction();

            $data['user_id'] = Auth::user()->id;
            $data['canceled'] = 0;
            $event = $this->eventRepository->create($data);

            DB::commit();
        } catch (Exception $exception) {
            DB::rollBack();
            throw $exception;
        }
        return new JsonResponse(new EventResource($event), JsonResponse::HTTP_CREATED);
    }

    /**
     * Get a event
     * @param int $id
     * @return JsonResponse
     */
    public function show($id)
    {
        $event = $this->eventRepository->find($id);

        if (!$event) {
            return new JsonResponse(['message' => 'event not found!'], JsonResponse::HTTP_NOT_FOUND);
        }

        return new JsonResponse(new EventResource($event), JsonResponse::HTTP_OK);
    }

    /**
     * updates a event
     * @param EventRequest $request
     * @param int $id
     * @return JsonResponse
     */
    public function update($id, EventRequest $request)
    {

        $event = $this->eventRepository->find($id);
        if (!$event) {
            return new JsonResponse(['message' => 'Event not found!'], JsonResponse::HTTP_NOT_FOUND);
        }


        if ($event->user_id != Auth::user()->id) {
            return new JsonResponse(['message' => 'You cannot edit this event because you are not the creator!'], JsonResponse::HTTP_BAD_REQUEST);
        }

        if ($event->cancel) {
            return new JsonResponse(['message' => 'This event cannot be edited because it was canceled!'], JsonResponse::HTTP_BAD_REQUEST);
        }

        $data = $request->all();

        try {
            DB::beginTransaction();

            $event = $this->eventRepository->update($data, $id);
            $event = $this->eventRepository->find($id);

            DB::commit();

            return new JsonResponse(new EventResource($event), JsonResponse::HTTP_OK);
        } catch (Exception $exception) {
            DB::rollBack();
            return new ErrorResponse($exception);
        }
    }

    /**
     * Cancels a event
     * @param int $id
     * @return JsonResponse
     */
    public function destroy($id)
    {
        $event = $this->eventRepository->find($id);
        if (!$event) {
            return new JsonResponse(['message' => 'Event not found!'], JsonResponse::HTTP_NOT_FOUND);
        }

        if ($event->user_id != Auth::user()->id) {
            return new JsonResponse(['message' => 'You cannot cancel this event because you are not the creator'], JsonResponse::HTTP_BAD_REQUEST);
        }

        if ($event->canceled === 1) {
            return new JsonResponse(['message' => 'This event has already been canceled'], JsonResponse::HTTP_BAD_REQUEST);
        }

        try {
            DB::beginTransaction();

            $event = $this->eventRepository->update(['canceled' => 1], $id);
            $event = $this->eventRepository->find($id);
            DB::commit();

            return new JsonResponse(['message' => 'Event successfully canceled!'], JsonResponse::HTTP_OK);
        } catch (Exception $exception) {
            DB::rollBack();
            return new ErrorResponse($exception);
        }
    }

    /**
     * Invites all friends or a list of friends to an event
     * @param $id
     * @param EventInvitationRequest $request
     * @return JsonResponse
     * @throws Exception
     */
    public function inviteEvent($id, EventInvitationRequest $request)
    {
        $data = $request->validated();

        $event = $this->eventRepository->find($id);
        if (!$event) {
            return new JsonResponse(['message' => 'Event not found!'], JsonResponse::HTTP_NOT_FOUND);
        }

        if ($event->user_id != Auth::user()->id) {
            return new JsonResponse(['message' => 'You can only send invitations to events that you have created!'], JsonResponse::HTTP_BAD_REQUEST);
        }

        if ($event->canceled === 1) {
            return new JsonResponse(['message' => 'You can not invite friends for a canceled event!'], JsonResponse::HTTP_BAD_REQUEST);
        }

        if ($request->type != 'all' and $this->friendshipRepository->verifyFriends($data['users_id'])) {
            return new JsonResponse(['message' => 'You can only invite your friends!'], JsonResponse::HTTP_BAD_REQUEST);
        }

        try {
            DB::beginTransaction();

            $this->eventInvitationRepository->inviteFriends($request, $data, $event);

            DB::commit();

            return new JsonResponse(['message' => 'Invitations sent successfully'], JsonResponse::HTTP_OK);

        } catch (Exception $exception) {
            DB::rollBack();
            throw $exception;
        }
    }

    /**
     * update status of a event invitation
     * @param $id_event
     * @param $id_event_invitation
     * @param EventInvitationRequest $request
     * @return JsonResponse
     */
    public function updateEventInvitation($id_event, $id_event_invitation, EventInvitationRequest $request)
    {

        $event_invitation = $this->eventInvitationRepository->find($id_event_invitation);
        if (!$event_invitation) {
            return new JsonResponse(['message' => 'Event invitation not found!'], JsonResponse::HTTP_NOT_FOUND);
        }

        if ($event_invitation->event->canceled === 1) {
            return new JsonResponse(['message' => 'This event was canceled!'], JsonResponse::HTTP_BAD_REQUEST);
        }

        if (time() > strtotime($event_invitation->event->date . ' ' . $event_invitation->event->time)) {
            return new JsonResponse(['message' => 'you cannot change the status of the invitation, because this event has already happened!'], JsonResponse::HTTP_BAD_REQUEST);
        }

        $data = $request->all();

        try {
            DB::beginTransaction();

            $event = $this->eventInvitationRepository->update($data, $id_event_invitation);
            $event = $this->eventInvitationRepository->find($id_event_invitation);

            DB::commit();

            return new JsonResponse(new EventInvitationResource($event), JsonResponse::HTTP_OK);
        } catch (Exception $exception) {
            DB::rollBack();
            return new ErrorResponse($exception);
        }
    }

    /**
     * Lists all the events of the logged user
     * @param Request $request
     * @return JsonResponse
     */
    public function myEvents(Request $request)
    {

        $events = $this->eventRepository->myEvents($request);

        return new JsonResponse(EventResource::collection($events), JsonResponse::HTTP_OK);
    }

    /**
     * Lists all the events of the logged user
     * @param Request $request
     * @return JsonResponse
     */
    public function invitations(Request $request)
    {

        $events = $this->eventInvitationRepository->paginateRequest(array_merge($request->all(), ['user_id' => Auth::user()->id]));

        return new JsonResponse(EventInvitationResource::collection($events), JsonResponse::HTTP_OK);
    }

}
