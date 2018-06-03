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
            'city_name'     => $this->district->city->city_name,
            'city_id'       => $this->district->city_id,
            'district_name' => $this->district->district_name,
            'district_id'   => $this->district_id,
            'id'            => $this->id,
            'lat'           => $this->lat,
            'lng'           => $this->lng,
            'preparetime'   => $this->preparetime,
            'status_name'   => $this->status->store_status_name,
            'status_id'     => $this->status_id,
            'address'       => $this->store_address,
            'avatar'        => $this->store_avatar,
            'name'          => $this->store_name,
            'phone'         => $this->store_phone,
            'slug'          => $this->store_slug,
            'type_name'     => $this->type->type_name,
            'type_id'       => $this->type_id,
            'type_icon'     => $this->type->type_icon,
            'show'          => $this->store_show,
            'verified'      => $this->verified,
            'user'          => $this->user,
            'activities'    => $this->whenLoaded('activities', function() {
                return $this->activities->map(function($query) {
                    $query->times = unserialize($query->pivot->times);
                    return $query;
                });
            }),
            'coupon_title'  => $this->coupons->map(function($query) {
                return $query->title;
            })->sortByDesc('created_at')->take(1)->first(),
            'toppings'      => $this->whenLoaded('toppings', function() {
                return ToppingResource::collection($this->toppings);
            })
        ];
    }
}
