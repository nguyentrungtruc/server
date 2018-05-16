<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Resources\Json\JsonResource;
class CityResource extends JsonResource
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
            'slug'             => $this->city_slug,
            'country_id'       => $this->country_id,
            'show'             => $this->city_show,
            'delivery_actived' => $this->delivery_actived,
            'lat'              => $this->lat,
            'lng'              => $this->lng,
            'zipcode'          => $this->zipcode,
            'country'          => $this->country,
            'service'          => new ServiceResource($this->service),
            'deliveries'       => empty($this->deliveries) ? null : $this->deliveries->map(function($query) {
                return collect(['id' => $query->id,'description' => $query->description, 'from' => $query->from, 'to' => $query->to, 'price'=> $query->pivot->price]);
            }),
            ''
        ];
    }
}
