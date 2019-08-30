<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Resources\Json\JsonResource;

class StoreStatusResource extends JsonResource
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
            'id'          => $this->id,
            'name'        => $this->store_status_name,
            'description' => $this->store_status_description,
            'color'       => $this->color,
            'createdAt'   => $this->created_at,
            'updatedAt'   => $this->updated_at
        ];
    }
}
