<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Resources\Json\JsonResource;

class DeliveryResource extends JsonResource
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
            'id'               => $this->id,
            'name'             => $this->city_name,
            'country_id'       => $this->country_id,
            'show'             => $this->city_show,
            'delivery_actived' => $this->delivery_actived,
            'lat'              => $this->lat,
            'lng'              => $this->lng,
            'zipcode'          => $this->zipcode,
            'deliveries'       => $this->deliveries->map(function($query) {
                return collect(['id' => $query->id,'description' => $query->description, 'from' => $query->from, 'to' => $query->to, 'price'=> $query->pivot->price]);
            }),
            'service'          => $this->whenLoaded('service', function() {
                return new ServiceResource($this->service);
            })
        ];
    }
}
