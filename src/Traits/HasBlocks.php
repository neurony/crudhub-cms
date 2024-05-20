<?php

namespace Zbiller\CrudhubCms\Traits;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;
use Zbiller\CrudhubCms\Contracts\BlockModelContract;
use Zbiller\CrudhubCms\Contracts\BlockTraitContract;
use Zbiller\CrudhubCms\Models\Block;

trait HasBlocks
{
    /**
     * @return array
     */
    abstract public function getBlockLocations(): array;

    /**
     * @return BlockTraitContract|null
     */
    public function inheritBlocksFrom(): ?BlockTraitContract
    {
        return null;
    }

    /**
     * @return MorphToMany
     */
    public function blocks()
    {
        $block = config('crudhub-cms.bindings.models.block_model', Block::class);

        return $this->morphToMany($block, 'blockable')->withPivot([
            'id', 'location', 'ord'
        ])->withTimestamps();
    }

    /**
     * @param string $location
     * @return Collection
     */
    public function getBlocksInLocation(string $location): Collection
    {
        return $this->blocks()->where('location', $location)->orderBy('ord')->get();
    }

    /**
     * @param string $location
     * @return Collection
     */
    public function getBlocksOfLocation(string $location): Collection
    {
        $items = new Collection();
        $blocks = App::make(BlockModelContract::class)->alphabetically()->get();

        foreach ($blocks as $block) {
            $types = (array)config('crudhub-cms.blocks.types', []);
            $class = $types[$block->type]['composer_class'] ?? null;

            if (class_exists($class) && in_array($location, $class::$locations)) {
                $items->push($block);
            }
        }

        return $items;
    }

    /**
     * @param string $location
     * @return Collection
     */
    public function getInheritedBlocks(string $location): Collection
    {
        $inheritor = $this->inheritBlocksFrom();

        if ($inheritor instanceof BlockTraitContract) {
            return $inheritor->getBlocksInLocation($location);
        }

        return $this->getBlocksInLocation($location);
    }

    /**
     * @param string $location
     * @param bool $inherits
     * @return string
     */
    public function renderBlocks(string $location, bool $inherits = true): string
    {
        $html = '';

        $locationBlocks = $this->getBlocksInLocation($location);

        if ($locationBlocks->isNotEmpty()) {
            foreach ($locationBlocks as $block) {
                $html .= View::make("blocks_{$block->type}::front")->with([
                    'model' => $block,
                ])->render();
            }

            return $html;
        }

        if ($inherits === true) {
            $inheritedBlocks = $this->getInheritedBlocks($location);

            if ($inheritedBlocks->isNotEmpty()) {
                foreach ($inheritedBlocks as $block) {
                    $html .= View::make("blocks_{$block->type}::front")->with([
                        'model' => $block,
                    ])->render();
                }

                return $html;
            }
        }

        return $html;
    }

    /**
     * [0 => [id => [location, ord], 1 => [id => [location, ord]...]
     *
     * @param array $blocks
     * @return void
     */
    public function saveBlocks(array $blocks = []): void
    {
        $this->blocks()->detach();

        if (is_array($blocks) && !empty($blocks)) {
            ksort($blocks);

            foreach ($blocks as $data) {
                foreach ($data as $id => $attributes) {
                    $block = App::make(BlockModelContract::class)->find($id);

                    if ($block && isset($attributes['location'])) {
                        $this->assignBlock($block, $attributes['location'], $attributes['ord'] ?? null);
                    }
                }
            }
        }
    }

    /**
     * @param BlockModelContract $block
     * @param string $location
     * @param int|null $order
     * @return void
     */
    public function assignBlock(BlockModelContract $block, string $location, ?int $order = null): void
    {
        if (!$order || !is_numeric($order)) {
            $order = 1;

            if ($last = $this->getBlocksInLocation($location)->last()) {
                if ($last->pivot && $last->pivot->ord) {
                    $order = $last->pivot->ord + 1;
                }
            }
        }

        $this->blocks()->save($block, [
            'location' => $location,
            'ord' => (int)$order,
        ]);
    }

    /**
     * @param BlockModelContract $block
     * @param string $location
     * @param int $pivot
     * @return void
     */
    public function unassignBlock(BlockModelContract $block, string $location, int $pivot): void
    {
        $this->blocks()
            ->newPivotStatementForId($block->getKey())
            ->where('location', $location)
            ->delete($pivot);
    }
}
