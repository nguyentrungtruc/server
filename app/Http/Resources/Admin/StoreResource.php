<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Resources\Json\JsonResource;

class StoreResource extends JsonResource
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
            'id'           => $this->id,
            'name'         => $this->store_name,
            'slug'         => $this->store_slug,
            'phone'        => $this->store_phone,
            'prepareTime'  => $this->preparetime,
            'address'      => $this->store_address,
            'lat'          => $this->lat,
            'lng'          => $this->lng,
            'discount'     => $this->discount,
            'avatar'       => $this->store_avatar,
            'isVerify'     => $this->verified,
            'isShow'       => $this->store_show,
            'priority'     => $this->priority,
            'cityId'       => $this->district->city_id,
            'cityName'     => $this->district->city->city_name,
            'districtId'   => $this->district_id,
            'districtName' => $this->district->district_name,
            'typeId'       => $this->type_id,
            'typeName'     => $this->type->type_name,
            'statusId'     => $this->status_id,
            'statusName'   => $this->status->store_status_name,
            'views'        => $this->views,
            'likes'        => $this->likes,
            'createdAt'    => $this->created_at,
            'updatedAt'    => $this->updated_at,
            'activities'    => $this->whenLoaded('activities', function() {
                return $this->activities->map(function($query) {
                    $query->times = unserialize($query->pivot->times);
                    return $query;
                });
            }),
        ];
    }
}
