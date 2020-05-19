<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class RoleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $roleArray = $this->resource->toArray();
        $permissions = [];
        foreach ($this->resource->permissions as $permission) {
            $permissions[] = [
                'id' => $permission->id,
                'view' => $permission->pivot->view ?? false,
                'create' => $permission->pivot->create ?? false,
                'edit' => $permission->pivot->edit ?? false,
                'delete' => $permission->pivot->delete ?? false
            ];
        }
        $roleArray['permissions'] = $permissions;
        return $roleArray;
    }
}
