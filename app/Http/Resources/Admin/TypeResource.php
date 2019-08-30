<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Resources\Json\JsonResource;

class TypeResource extends JsonResource
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
            'name'       => $this->type_name,
            'slug'       => $this->type_slug,
            'code'       => $this->code,
            'icon'       => $this->type_icon,
            'isShow'     => $this->type_show,
            'createdAt'  => $this->created_at,
            'updatedAt'  => $this->updated_at
        ];
    }
}
