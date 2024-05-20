<?php

namespace Zbiller\CrudhubCms\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Zbiller\Crudhub\Resources\MediaResource;

class PageResource extends JsonResource
{
    /**
     * @param Request $request
     * @return array<mixed>
     */
    public function toArray(Request $request): array
    {
        $images = $this->resource->getMedia('images');

        return array_merge(parent::toArray($request), [
            'image' => MediaResource::make($images->first()),
        ]);
    }
}
