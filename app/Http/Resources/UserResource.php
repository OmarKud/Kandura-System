<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $user = [
            "id" => $this->id,
            "name" => $this->name,
            "email" => $this->email,
            "created_at" => $this->created_at,
            "updated_at" => $this->updated_at,
'profile_image' => $this->profileImage
    ? new ImageResource($this->profileImage)
    : null,

        ];
        if(isset($this->access_token)){
            $user["token"] = $this->access_token;
        }
        return $user;
    }
}
