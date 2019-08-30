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
            'id'          => $this->id,
            'name'        => $this->city_name,
            'slug'        => $this->city_slug,
            'lat'         => $this->lat,
            'lng'         => $this->lng,
            'zipCode'     => $this->zipcode,
            'isShow'      => $this->city_show,
            'countryId'   => $this->country_id,
            'countryName' => $this->country->country_name,
            'country'     => new CountryResource($this->whenLoaded('countries')),
            'createdAt'   => $this->created_at,
            'updatedAt'   => $this->updated_at
        ];
    }
}
