<?php

namespace Zbiller\CrudhubCms\Filters;

use Zbiller\Crudhub\Filters\Filter;
use Zbiller\CrudhubCms\Contracts\MenuFilterContract;

class MenuFilter extends Filter implements MenuFilterContract
{
    /**
     * @return string
     */
    public function morph(): string
    {
        return 'and';
    }

    /**
     * @return array
     */
    public function filters(): array
    {
        return [
            'keyword' => [
                'operator' => Filter::OPERATOR_LIKE,
                'condition' => Filter::CONDITION_OR,
                'columns' => 'name,url',
            ],
            'type' => [
                'operator' => Filter::OPERATOR_EQUAL,
                'condition' => Filter::CONDITION_OR,
                'columns' => 'type',
            ],
            'active' => [
                'operator' => Filter::OPERATOR_EQUAL,
                'condition' => Filter::CONDITION_OR,
                'columns' => 'active',
            ],
            'start_date' => [
                'operator' => Filter::OPERATOR_DATE_GREATER_OR_EQUAL,
                'condition' => Filter::CONDITION_OR,
                'columns' => 'created_at',
            ],
            'end_date' => [
                'operator' => Filter::OPERATOR_DATE_SMALLER_OR_EQUAL,
                'condition' => Filter::CONDITION_OR,
                'columns' => 'created_at',
            ],
        ];
    }
}
