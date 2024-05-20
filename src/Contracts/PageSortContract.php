<?php

namespace Zbiller\CrudhubCms\Contracts;

interface PageSortContract
{
    /**
     * @return string
     */
    public function field(): string;

    /**
     * @return string
     */
    public function direction(): string;
}
