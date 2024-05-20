<?php

namespace Zbiller\CrudhubCms\Contracts;

interface MenuSortContract
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
