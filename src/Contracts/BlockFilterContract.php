<?php

namespace Zbiller\CrudhubCms\Contracts;

interface BlockFilterContract
{
    /**
     * @return string
     */
    public function morph(): string;

    /**
     * @return array
     */
    public function filters(): array;
}
