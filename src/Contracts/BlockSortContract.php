<?php

namespace Zbiller\CrudhubCms\Contracts;

interface BlockSortContract
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
