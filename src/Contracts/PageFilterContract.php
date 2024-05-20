<?php

namespace Zbiller\CrudhubCms\Contracts;

interface PageFilterContract
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
