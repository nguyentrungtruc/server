<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Resources\Json\JsonResource;

class CountryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return  [
            'id'          => $this->id,
            'name'        => $this->country_name,
            'lang'        => $this->lang,
            'slug'        => $this->country_slug,
            'lat'         => $this->lat,
            'lng'         => $this->lng,
            'dialingCode' => $this->dialingcode,
            'isShow'      => $this->country_show,
            'createdAt'   => $this->created_at,
            'updatedAt'   => $this->updated_at
        ];
    }
}
