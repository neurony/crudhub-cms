<?php

namespace Zbiller\CrudhubCms\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BlockResource extends JsonResource
{
    /**
     * @param Request $request
     * @return array<mixed>
     */
    public function toArray(Request $request): array
    {
        return array_merge(parent::toArray($request), [

        ]);
    }
}
