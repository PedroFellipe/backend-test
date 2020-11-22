<?php

namespace App\Http\Controllers;

use App\Http\Requests\FriendshipInvitationRequest;

use App\Http\Resources\FriendShipListResource;
use App\Http\Resources\FriendshipResource;
use App\Repository\FriendshipRepository;
use App\Http\Resources\UserResource;
use Illuminate\Routing\Controller;
use Illuminate\Http\JsonResponse;
use App\Responses\ErrorResponse;
use App\Models\Friendship;
use Illuminate\Http\Request;
use Auth;
use DB;

class FriendshipController extends Controller
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
        return new JsonResponse(FriendShipListResource::collection($this->friendshipRepository->friendshipList()), JsonResponse::HTTP_OK);
    }

    /**
     * Removes a friendship
     * @param int $id
     * @return JsonResponse
     */
    public function destroy($id)
    {

        $friendship = $this->friendshipRepository->find($id);
        if (!$friendship) {
            return new JsonResponse(['message' => 'Friendship not found!'], JsonResponse::HTTP_NOT_FOUND);
        }

        try {
            DB::beginTransaction();

            //Undo Friendship
            $this->friendshipRepository->delete($id);

            DB::commit();

            return new JsonResponse(['message' => 'Friendship removed!'], JsonResponse::HTTP_OK);
        } catch (\Exception $exception) {
            DB::rollBack();

            return new ErrorResponse($exception);
        }
    }
}
