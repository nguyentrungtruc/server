<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Resources\Json\JsonResource;

class DistrictResource extends JsonResource
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
            'id'        => $this->id,
            'cityId'    => $this->city_id,
            'cityName'  => $this->city->city_name,
            'city'      => new CityResource($this->whenLoaded('cities')),
            'name'      => $this->district_name,
            'slug'      => $this->district_slug,
            'lat'       => $this->lat,
            'lng'       => $this->lng,
            'isShow'    => $this->district_show,
            'createdAt' => $this->created_at,
            'updatedAt' => $this->updated_at
        ];
    }
}
