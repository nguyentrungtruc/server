<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
            'id'         => $this->id,
            'name'       => $this->name,
            'email'      => $this->email,
            'birthday'   => $this->birthday,
            'gender'     => $this->gender,
            'address'    => $this->address,
            'lat'        => $this->lat,
            'lng'        => $this->lng,
            'phone'      => $this->phone,
            'avatar'     => $this->image,
            'ownerStore' => $this->have_store,
            'isActive'   => $this->actived,
            'isBan'      => $this->banned,
            'roleId'     => $this->role_id,
            'points'     => $this->points,
            'createdAt'  => $this->created_at,
            'updatedAt'  => $this->updated_at
        ];
    }
}
