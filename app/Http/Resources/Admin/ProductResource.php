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
            'id'            => $this->id,
            'name'          => $this->name,
            '_name'         => $this->_name,
            'description'   => $this->description,
            'count'         => $this->count,
            'sizes'         => $this->sizes->map(function($query) {
                $query->price = $query->pivot->price;
                return $query;
            }),
            'haveTopping'   => $this->have_topping,
            'avatar'        => $this->image,
            'priority'      => $this->priority,
            'statusId'      => $this->status_id,
            'statusName'    => $this->status->product_status_name,
            'catalogueId'   => $this->catalogue_id,
            'catalogueName' => $this->catalogue->catalogue,
            'createdAt'     => $this->created_at,
            'updatedAt'     => $this->updated_at,
        ];
    }
}
