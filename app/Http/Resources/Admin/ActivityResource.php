<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Resources\Json\JsonResource;

class ActivityResource extends JsonResource
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
            'daysofweek' => $this->daysofweek,
            'number'     => $this->number,
            'createdAt'   => $this->created_at,
            'updatedAt'   => $this->updated_at
        ];
    }
}
