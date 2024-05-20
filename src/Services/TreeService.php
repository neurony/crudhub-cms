<?php

namespace Zbiller\CrudhubCms\Services;

use Illuminate\Support\Collection;
use Zbiller\CrudhubCms\Contracts\NodeTraitContract;
use Zbiller\CrudhubCms\Contracts\TreeServiceContract;

class TreeService implements TreeServiceContract
{
    /**
     * @var NodeTraitContract
     */
    protected NodeTraitContract $model;

    /**
     * @param NodeTraitContract $model
     */
    public function __construct(NodeTraitContract $model)
    {
        $this->model = $model;
    }

    /**
     * @return array
     */
    public function getTree(array $where = []): array
    {
        $roots = $this->model
            ->withoutGlobalScopes()
            ->with('children')
            ->whereIsRoot()
            ->where($where)
            ->defaultOrder()
            ->get();

        return $this->buildTree($roots);
    }

    /**
     * @param Collection $nodes
     * @return array
     */
    protected function buildTree(Collection $nodes): array
    {
        $tree = [];

        foreach ($nodes as $node) {
            $item = [
                'id' => $node->id,
                'name' => $node->name,
            ];

            if ($node->children->isNotEmpty()) {
                $item['children'] = $this->buildTree($node->children);
            }

            $tree[] = $item;
        }

        return $tree;
    }
}