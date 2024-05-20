<?php

namespace Zbiller\CrudhubCms\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Str;
use Kalnoy\Nestedset\NodeTrait;
use Zbiller\Crudhub\Traits\FiltersRecords;
use Zbiller\Crudhub\Traits\SortsRecords;
use Zbiller\CrudhubCms\Contracts\MenuableContract;
use Zbiller\CrudhubCms\Contracts\MenuModelContract;
use Zbiller\CrudhubCms\Contracts\NodeTraitContract;
use Zbiller\CrudhubCms\Exceptions\MenuException;

class Menu extends Model implements MenuModelContract, NodeTraitContract
{
    use FiltersRecords;
    use SortsRecords;
    use NodeTrait;

    /**
     * The database table.
     *
     * @var string
     */
    protected $table = 'menus';

    /**
     * @var string[]
     */
    protected $fillable = [
        'parent_id',
        'menuable_id',
        'menuable_type',
        'name',
        'url',
        'location',
        'type',
        'route',
        'active',
        'meta_data',
    ];

    /**
     * @var array<string, string>
     */
    protected $casts = [
        'active' => 'boolean',
        'meta_data' => 'array',
    ];

    /**
     * @var string[]
     */
    protected $appends = [
        'full_url',
    ];

    /**
     * @return void
     */
    public static function boot()
    {
        parent::boot();

        static::saving(function (Model $model) {
            if (!isset($model->attributes['type'])) {
                return;
            }

            switch ($model->attributes['type']) {
                case 'url':
                    $model->attributes['menuable_id'] = null;
                    $model->attributes['menuable_type'] = null;
                    $model->attributes['route'] = null;

                    break;
                case 'route':
                    $model->attributes['menuable_id'] = null;
                    $model->attributes['menuable_type'] = null;
                    $model->attributes['url'] = null;

                    break;
                default:
                    $types = (array)config('crudhub-cms.menus.types', []);

                    $model->attributes['url'] = null;
                    $model->attributes['route'] = null;
                    $model->attributes['menuable_type'] = $types[$model->attributes['type']];

                    break;
            }
        });

        static::updating(function (Model $model) {
            if ($model->isDirty('location') && $model->children()->count() > 0) {
                throw new MenuException('Failed updating location due to having children');
            }
        });

        static::deleting(function (Model $model) {
            if ($model->children()->count() > 0) {
                throw new MenuException('Deletion restricted due to having children');
            }
        });
    }

    /**
     * @return MorphTo
     */
    public function menuable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * @return string|null
     */
    public function getFullUrlAttribute(): ?string
    {
        if ($this->attributes['url']) {
            return Str::startsWith($this->attributes['url'], ['http', 'www']) ?
                $this->attributes['url'] : url('/' . trim($this->attributes['url'], '/'));
        }

        if ($this->attributes['route']) {
            return route($this->attributes['route']);
        }

        if ($this->menuable instanceof MenuableContract) {
            return $this->menuable->getMenuableUrl();
        }

        return null;
    }
}
