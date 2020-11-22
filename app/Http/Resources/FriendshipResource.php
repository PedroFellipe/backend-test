<?php

namespace App\Http\Resources;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;

class FriendshipResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'user_that_make_invite' => new UserResource($this->first_user) ,
            'user_invited' => new UserResource($this->second_user),
            'confirmed' => $this->confirmed
        ];
    }
}
