<?php

namespace Zbiller\CrudhubCms\Filters;

use Zbiller\Crudhub\Filters\Filter;
use Zbiller\CrudhubCms\Contracts\BlockFilterContract;

class BlockFilter extends Filter implements BlockFilterContract
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
                'columns' => 'name',
            ],
            'type' => [
                'operator' => Filter::OPERATOR_EQUAL,
                'condition' => Filter::CONDITION_OR,
                'columns' => 'type',
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
