<?php

namespace Zbiller\CrudhubCms\Models;

use Illuminate\Database\Eloquent\Model;
use Kalnoy\Nestedset\NodeTrait;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Zbiller\Crudhub\Traits\AutoSavesMediaFiles;
use Zbiller\Crudhub\Traits\HasGlobalMediaConversions;
use Zbiller\CrudhubCms\Contracts\NodeTraitContract;
use Zbiller\CrudhubCms\Contracts\PageModelContract;
use Zbiller\Crudhub\Traits\FiltersRecords;
use Zbiller\Crudhub\Traits\SortsRecords;
use Zbiller\CrudhubCms\Exceptions\PageException;
use Zbiller\CrudhubCms\Traits\HasBlocks;

class Page extends Model implements PageModelContract, NodeTraitContract, HasMedia
{
    use HasBlocks;
    use FiltersRecords;
    use SortsRecords;
    use NodeTrait;
    use InteractsWithMedia;
    use AutoSavesMediaFiles;
    use HasGlobalMediaConversions;

    /**
     * The database table.
     *
     * @var string
     */
    protected $table = 'pages';

    /**
     * @var string[]
     */
    protected $fillable = [
        'parent_id',
        'name',
        'slug',
        'type',
        'identifier',
        'active',
        'meta_data',
        'meta_tags',
    ];

    /**
     * @var array<string, string>
     */
    protected $casts = [
        'active' => 'boolean',
        'meta_data' => 'array',
        'meta_tags' => 'array',
    ];

    /**
     * @return void
     */
    public static function boot()
    {
        parent::boot();

        static::saving(function (Model $model) {
            $model->slug = trim($model->slug, '/');
        });

        static::deleting(function (Model $model) {
            if ($model->children()->count() > 0) {
                throw new PageException('Deletion restricted due to having children');
            }
        });
    }

    /**
     * @return string
     */
    public function getRouteControllerAttribute(): string
    {
        $types = (array)config('crudhub-cms.pages.types', []);

        return $types[$this->attributes['type']]['controller'] ?? '';
    }

    /**
     * @return string
     */
    public function getRouteActionAttribute(): string
    {
        $types = (array)config('crudhub-cms.pages.types', []);

        return $types[$this->attributes['type']]['action'] ?? '';
    }

    /**
     * @return array
     */
    public function getBlockLocations(): array
    {
        $types = (array)config('crudhub-cms.pages.types', []);

        return $types[$this->type]['locations'] ?? [];
    }

    /**
     * @return void
     */
    public function registerMediaCollections(): void
    {
        $this
            ->addMediaCollection('images')
            ->useDisk(config('crudhub.media.disk_name'))
            ->singleFile()
            ->withResponsiveImages()
            ->registerMediaConversions(function (Media $media) {
                $this
                    ->addMediaConversion('landscape')
                    ->width(300)
                    ->height(100);

                $this
                    ->addMediaConversion('portrait')
                    ->width(100)
                    ->height(300);
            });
    }

    /**
     * @param Media|null $media
     * @throws \Spatie\Image\Exceptions\InvalidManipulation
     */
    public function registerMediaConversions(Media $media = null): void
    {
        $this->registerGlobalMediaConversions($media);
    }
}
