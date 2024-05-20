<?php

namespace Zbiller\CrudhubCms\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\App;
use Illuminate\Validation\Rule;
use Zbiller\CrudhubCms\Contracts\BlockModelContract;

class BlockRequest extends FormRequest
{
    /**
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * @return array
     */
    public function rules()
    {
        return [
            'name' => [
                'required',
                Rule::unique(App::make(BlockModelContract::class)->getTable(), 'name')
                    ->ignore($this->route('block')?->getKey() ?: null),
            ],
            'type' => [
                'required',
                Rule::in(array_keys((array)config('crudhub-cms.blocks.types', []))),
            ],
        ];
    }
}
