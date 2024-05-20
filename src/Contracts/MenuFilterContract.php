<?php

namespace Zbiller\CrudhubCms\Contracts;

interface MenuFilterContract
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
