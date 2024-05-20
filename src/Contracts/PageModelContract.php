<?php

namespace Zbiller\CrudhubCms\Contracts;

interface PageModelContract
{
    /**
     * @return string
     */
    public function getRouteControllerAttribute(): string;

    /**
     * @return string
     */
    public function getRouteActionAttribute(): string;
}
