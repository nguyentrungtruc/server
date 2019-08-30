<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Resources\Json\JsonResource;

class CatalogueResource extends JsonResource
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
            'name'      => $this->catalogue,
            '_name'     => $this->_catalogue,
            'priority'  => $this->priority,
            'isShow'    => $this->catalogue_show,
            'storeId'   => $this->store_id,
            'slug'      => $this->slug,
            'createdAt' => $this->created_at,
            'updatedAt' => $this->updated_at,
            // 'products' => ProductResource::collection($this->whenLoaded('products', function() {
            //     return $this->products->sortByDesc('priority')->sortBy('name');
            // }))
        ];
    }
}
