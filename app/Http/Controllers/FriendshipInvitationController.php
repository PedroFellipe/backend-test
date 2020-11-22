<?php

namespace App\Http\Controllers;

use App\Http\Requests\FriendshipInvitationRequest;

use App\Http\Resources\FriendshipResource;
use App\Repository\FriendshipRepository;
use Illuminate\Routing\Controller;
use Illuminate\Http\JsonResponse;
use App\Responses\ErrorResponse;
use Auth;
use DB;


class FriendshipInvitationController extends Controller
{

    protected $friendshipRepository;

    public function __construct(FriendshipRepository $friendship)
    {
        $this->friendshipRepository = $friendship;

    }

    /**
     * Lists all friendships requests
     * @return JsonResponse
     */
    public function index()
    {

        return new JsonResponse(FriendshipResource::collection($this->friendshipRepository->friendshipRequests()), JsonResponse::HTTP_OK);
    }


    /**
     * Creates a friendship request
     * @param FriendshipInvitationRequest $request
     * @return JsonResponse
     * @throws \Exception
     */
    public function store(FriendshipInvitationRequest $request)
    {
        $data = $request->validated();
        if ($this->friendshipRepository->alreadyInvited($data['email'])) {
            return new JsonResponse(['message' => 'you\'ve already made a friend invitation to that person!'], JsonResponse::HTTP_BAD_REQUEST);
        }

        if ($this->friendshipRepository->alreadyInvitated($data['email'])) {
            return new JsonResponse(['message' => 'that person has already invited you to be your friend!'], JsonResponse::HTTP_BAD_REQUEST);
        }

        if ($this->friendshipRepository->alreadyFriends($data['email'])) {
            return new JsonResponse(['message' => 'you\'re already friends!'], JsonResponse::HTTP_BAD_REQUEST);
        }

        if (Auth::user()->email === $data['email']) {
            return new JsonResponse(['message' => 'you can\'t invite yourself!'], JsonResponse::HTTP_BAD_REQUEST);
        }

        try {
            DB::beginTransaction();

            $invite = $this->friendshipRepository->invite($data['email']);

            DB::commit();
        } catch (\Exception $exception) {
            DB::rollBack();
            throw $exception;
        }

        return new JsonResponse([new FriendshipResource($invite)], JsonResponse::HTTP_CREATED);

    }

    /**
     * Accepts a friendship request
     * @param int $id
     * @return JsonResponse
     */
    public function update($id)
    {

        $friendship = $this->friendshipRepository->find($id);
        if (!$friendship) {
            return new JsonResponse(['message' => 'Friendship request not found!!'], JsonResponse::HTTP_NOT_FOUND);
        }

        if ($friendship->confirmed === 1) {
            return new JsonResponse(['message' => 'This friend request has already been accepted !!'], JsonResponse::HTTP_BAD_REQUEST);
        }

        if ($friendship->second_user_id != Auth::user()->id) {
            return new JsonResponse(['message' => 'This friend request is not for you!!'], JsonResponse::HTTP_BAD_REQUEST);
        }

        try {
            DB::beginTransaction();

            $friendship = $this->friendshipRepository->update(['confirmed' => 1, 'second_user_id' => Auth::user()->id], $id);
            $friendship = $this->friendshipRepository->find($id);

            DB::commit();

            return new JsonResponse(new FriendshipResource($friendship), JsonResponse::HTTP_OK);
        } catch (\Exception $exception) {
            DB::rollBack();
            return new ErrorResponse($exception);
        }
    }

    /**
     * Declines a friendship request
     * @param int $id
     * @return JsonResponse
     */
    public function destroy($id)
    {
        $friendship = $this->friendshipRepository->find($id);

        if (!$friendship) {
            return new JsonResponse(['message' => 'Friendship request not found!!'], JsonResponse::HTTP_NOT_FOUND);
        }

        if ($friendship->confirmed === 1) {
            return new JsonResponse(['message' => 'This friend request has already been accepted !!'], JsonResponse::HTTP_NOT_FOUND);
        }

        if ($friendship->second_user_id != Auth::user()->id) {
            return new JsonResponse(['message' => 'This friend request is not for you!!'], JsonResponse::HTTP_BAD_REQUEST);
        }


        try {
            DB::beginTransaction();

            $this->friendshipRepository->delete($id);

            DB::commit();

            return new JsonResponse(['message' => 'Friendship deleted'], JsonResponse::HTTP_OK);
        } catch (\Exception $exception) {
            DB::rollBack();

            return new ErrorResponse($exception);
        }
    }
}
