<?php

namespace Zbiller\CrudhubCms\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\App;
use Illuminate\Validation\Rule;
use Zbiller\CrudhubCms\Contracts\PageModelContract;

class PageRequest extends FormRequest
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
            'parent_id' => [
                'sometimes',
                'nullable',
                Rule::exists(App::make(PageModelContract::class)->getTable(), 'id')
            ],
            'name' => [
                'required',
                Rule::unique(App::make(PageModelContract::class)->getTable(), 'name')
                    ->ignore($this->route('page')?->getKey() ?: null),
            ],
            'type' => [
                'required',
                Rule::in(array_keys((array)config('crudhub-cms.pages.types', []))),
            ],
            'identifier' => [
                'nullable',
                Rule::unique(App::make(PageModelContract::class)->getTable(), 'identifier')
                    ->ignore($this->route('page')?->getKey() ?: null),
            ],
            'slug' => [
                'required',
                Rule::unique(App::make(PageModelContract::class)->getTable(), 'slug')
                    ->ignore($this->route('page')?->getKey() ?: null),
            ],
            'active' => [
                'boolean',
            ],
        ];
    }

    /**
     * @return $this
     */
    public function merged(): self
    {
        return $this->mergeActive();
    }

    /**
     * @return $this
     */
    protected function mergeActive(): self
    {
        if (!$this->filled('active')) {
            $this->merge([
                'active' => false,
            ]);
        }

        return $this;
    }
}
