<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        $user = [
            'id' => $this->resource->id,
            'name' => $this->resource->name,
            'email' => $this->resource->email,
            'active' => $this->resource->active,
            'admin' => $this->resource->admin
        ];
        if ($this->resource->role) {
            $user['role'] = $this->resource->role;
            $user['role_id'] = $this->resource->role_id;
        }
        return $user;
    }
}
