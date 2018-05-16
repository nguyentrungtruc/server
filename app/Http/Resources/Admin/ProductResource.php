<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
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
            'catalogue_id' => $this->catalogue_id,
            'count'        => $this->count,            
            'created_at'   => $this->created_at,
            'id'           => $this->id,
            'name'         => $this->name,
            '_name'        => $this->_name,
            'image'        => $this->image,
            'price'        => $this->price,
            'description'  => $this->description,
            'status_id'    => $this->status_id,
            'status'       => $this->status,
            'haveSize'     => $this->have_size,
            'haveTopping'  => $this->haveTopping,
            'updated_at'   => $this->updated_at
        ];
    }
}
