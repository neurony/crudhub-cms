<?php

namespace Zbiller\CrudhubCms\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\App;
use Illuminate\Validation\Rule;
use Zbiller\CrudhubCms\Contracts\MenuModelContract;

class MenuRequest extends FormRequest
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
                Rule::exists(App::make(MenuModelContract::class)->getTable(), 'id')
            ],
            'name' => [
                'required',
            ],
            'type' => [
                'required',
            ],
            'location' => [
                'required',
                Rule::in(config('crudhub-cms.menus.locations')),
            ],
            'url' => [
                'required_without_all:menuable_id,route',
            ],
            'route' => [
                'required_without_all:url,menuable_id',
            ],
            'menuable_id' => [
                'required_without_all:url,route',
            ],
        ];
    }

    /**
     * @return array
     */
    public function attributes()
    {
        return [
            'url.required_without_all' => 'url',
            'menuable_id.required_without_all'  => 'url',
        ];
    }

    /**
     * @param string|null $location
     * @return $this
     */
    public function merged(?string $location = null): self
    {
        if ($location) {
            $this->mergeLocation($location);
        }

        return $this->mergeActive()->mergeNewWindow();
    }

    /**
     * @param string $location
     * @return $this
     */
    protected function mergeLocation(string $location): self
    {
        $this->merge([
            'location' => $location
        ]);

        return $this;
    }

    /**
     * @return $this
     */
    protected function mergeActive(): self
    {
        if (!$this->filled('active')) {
            $this->merge([
                'active' => false
            ]);
        }

        return $this;
    }

    /**
     * @return $this
     */
    protected function mergeNewWindow(): self
    {
        if (!$this->filled('meta_data.new_window')) {
            $this->merge([
                'meta_data' => [
                    'new_window' => '0',
                ]
            ]);
        }

        return $this;
    }
}
