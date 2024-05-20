<?php

namespace Zbiller\CrudhubCms\Sorts;

use Zbiller\Crudhub\Sorts\Sort;
use Zbiller\CrudhubCms\Contracts\MenuSortContract;

class MenuSort extends Sort implements MenuSortContract
{
    /**
     * @return string
     */
    public function field(): string
    {
        return Sort::DEFAULT_SORT_FIELD;
    }

    /**
     * @return string
     */
    public function direction(): string
    {
        return Sort::DEFAULT_DIRECTION_FIELD;
    }
}
