<?php

namespace App\Http\Resources;

use JD\Cloudder\Facades\Cloudder;
use Illuminate\Http\Resources\Json\JsonResource;

class User extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
		$this->pic = Cloudder::secureShow($this->pic);

        return [
            'id' => $this->id,
            'name' => $this->name,
            'pic' => Cloudder::secureShow($this->pic)
        ];
    }
}
