<?php

namespace Zbiller\CrudhubCms\Contracts;

interface MenuableContract
{
    /**
     * @return int
     */
    public function getMenuableId(): int;

    /**
     * @return string
     */
    public function getMenuableName(): string;

    /**
     * @return string
     */
    public function getMenuableUrl(): string;
}
