<?php

namespace Zbiller\CrudhubCms\Controllers;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Zbiller\CrudhubCms\Contracts\PageModelContract;
use Zbiller\CrudhubCms\Contracts\PageFilterContract;
use Zbiller\CrudhubCms\Contracts\PageSortContract;
use Zbiller\Crudhub\Facades\Flash;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Throwable;
use Zbiller\Crudhub\Resources\Resource;
use Zbiller\Crudhub\Traits\BulkDestroysRecords;
use Zbiller\Crudhub\Traits\PartiallyUpdatesRecords;
use Zbiller\CrudhubCms\Exceptions\PageException;
use Zbiller\CrudhubCms\Requests\PageRequest;
use Zbiller\CrudhubCms\Services\TreeService;

class PageController
{
    use AuthorizesRequests;
    use ValidatesRequests;
    use BulkDestroysRecords;
    use PartiallyUpdatesRecords;

    /**
     * @param Request $request
     * @param PageFilterContract $filter
     * @param PageSortContract $sort
     * @return \Inertia\Response
     */
    public function index(Request $request, PageFilterContract $filter, PageSortContract $sort)
    {
        $query = App::make(PageModelContract::class)->query();
        $filters = $request->all();

        unset($filters['parent']);
        unset($filters['page']);
        unset($filters['per_page']);

        if (empty(array_filter($filters))) {
            $query->when($request->filled('parent'), function ($query) use ($request) {
                $query->whereParentId($request->get('parent'));
            })->when(!$request->filled('parent'), function ($query) {
                $query->whereIsRoot();
            });
        } else {
            $query->filtered($request->all(), $filter);
        }

        if ($request->filled('sort_by')) {
            $query->sorted($request->all(), $sort);
        } else {
            $query->defaultOrder();
        }

        $items = $query->paginate($request->get('per_page', 10))->withQueryString();

        return Inertia::render('Pages/Index', [
            'items' => Resource::collection('page_resource', $items, 'crudhub-cms'),
            'options' => [
                'tree' => (new TreeService(App::make(PageModelContract::class)))->getTree(),
                'types' => $this->getTypes(),
            ],
        ]);
    }

    /**
     * @param int|null $parent
     * @return \Inertia\Response
     */
    public function create(?int $parent = null)
    {
        $parent = App::make(PageModelContract::class)->find($parent);

        return Inertia::render('Pages/Create', [
            'parent' => $parent instanceof PageModelContract && $parent->exists ? Resource::make('page_resource', $parent, 'crudhub-cms') : null,
            'options' => [
                'types' => $this->getTypes(),
            ],
        ]);
    }

    /**
     * @return \Illuminate\Http\RedirectResponse|void
     */
    public function store()
    {
        /** @var PageRequest $request */
        $request = $this->initRequest();

        try {
            DB::beginTransaction();

            $page = App::make(PageModelContract::class)->create($request->all());

            DB::commit();

            Flash::success('Record created successfully!');

            return Redirect::saved($request, route('admin.pages.index'), route('admin.pages.edit', $page->getKey()));
        } catch (Throwable $e) {
            DB::rollBack();

            Flash::error(exception: $e);
        }
    }

    /**
     * @param PageModelContract $page
     * @return \Inertia\Response
     */
    public function edit(PageModelContract $page)
    {
        $parent = App::make(PageModelContract::class)->find($page->parent_id);

        return Inertia::render('Pages/Edit', [
            'item' => Resource::make('page_resource', $page, 'crudhub-cms'),
            'parent' => $parent instanceof PageModelContract && $parent->exists ? Resource::make('page_resource', $parent, 'crudhub-cms') : null,
            'options' => [
                'types' => $this->getTypes(),
            ],
        ]);
    }

    /**
     * @param PageModelContract $page
     * @return \Illuminate\Http\RedirectResponse|void
     */
    public function update(PageModelContract $page)
    {
        /** @var PageRequest $request */
        $request = $this->initRequest();

        try {
            DB::beginTransaction();

            $page->update($request->all());

            DB::commit();

            Flash::success('Record updated successfully!');

            return Redirect::saved($request, route('admin.pages.index'), route('admin.pages.edit', $page->getKey()));
        } catch (Throwable $e) {
            DB::rollBack();

            Flash::error(exception: $e);
        }
    }

    /**
     * @param Request $request
     * @param PageModelContract $page
     * @return \Illuminate\Http\RedirectResponse|void
     */
    public function destroy(Request $request, PageModelContract $page)
    {
        try {
            DB::beginTransaction();

            $page->delete();

            DB::commit();

            Flash::success('Record deleted successfully!');

            return Redirect::deleted(route('admin.pages.index'));
        } catch (PageException $e) {
            DB::rollBack();

            Flash::error($e->getMessage(), $e);
        } catch (Throwable $e) {
            DB::rollBack();

            Flash::error(exception: $e);
        }
    }

    /**
     * @return string
     */
    public function bulkDestroyModel(): string
    {
        return PageModelContract::class;
    }

    /**
     * @param Request $request
     * @return array
     */
    public function bulkDestroyIds(Request $request): array
    {
        return (array)$request->get('ids') ?? [];
    }

    /**
     * @return string
     */
    public function partialUpdateModel(): string
    {
        return PageModelContract::class;
    }

    /**
     * @param Request $request
     * @return array
     */
    public function partialUpdateData(Request $request): array
    {
        return [
            'active' => $request->get('active', false),
        ];
    }

    /**
     * @return mixed
     */
    protected function initRequest(): mixed
    {
        $request = config('crudhub-cms.bindings.form_requests.page_form_request', PageRequest::class);

        return App::make($request)->merged();
    }

    /**
     * @return array
     */
    protected function getTypes(): array
    {
        $types = [];

        foreach (array_keys((array)config('crudhub-cms.pages.types', [])) ?? [] as $type) {
            $types[] = [
                'value' => $type,
                'label' => Str::of($type)->ucfirst()->replace(['-', '_'], ' ')->toString(),
            ];
        }

        return $types;
    }
}
