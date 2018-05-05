<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\User as UserResource;

class Review extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
		return [
			'stars' => $this->stars, 
			'body' => $this->body, 
			'user' => new UserResource($this->user),
			'reviewable_id' => $this->reviewable_id, 
			'reviewable_type' => $this->reviewable_type,
			'created_at' => $this->created_at,
			'updated_at' => $this->updated_at,
		];
    }
}
