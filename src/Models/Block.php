<?php

namespace Zbiller\CrudhubCms\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Zbiller\CrudhubCms\Contracts\BlockModelContract;
use Zbiller\Crudhub\Traits\FiltersRecords;
use Zbiller\Crudhub\Traits\SortsRecords;

class Block extends Model implements BlockModelContract
{
    use FiltersRecords;
    use SortsRecords;

    /**
     * The database table.
     *
     * @var string
     */
    protected $table = 'blocks';

    /**
     * @var string[]
     */
    protected $fillable = [
        'name',
        'type',
        'meta_data',
    ];

    /**
     * @var array<string, string>
     */
    protected $casts = [
        'meta_data' => 'array',
    ];

    /**
     * @param string $related
     * @return MorphToMany
     */
    public function blockables(string $related): MorphToMany
    {
        return $this->morphedByMany($related, 'blockable')->withPivot([
            'id', 'location', 'ord'
        ])->withTimestamps();
    }
}
