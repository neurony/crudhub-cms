<?php

namespace Zbiller\CrudhubCms\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;
use Zbiller\CrudhubCms\Contracts\MenuModelContract;
use Zbiller\CrudhubCms\Contracts\MenuFilterContract;
use Zbiller\CrudhubCms\Contracts\MenuSortContract;
use Zbiller\Crudhub\Facades\Flash;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Throwable;
use Zbiller\Crudhub\Resources\Resource;
use Zbiller\Crudhub\Traits\BulkDestroysRecords;
use Zbiller\Crudhub\Traits\PartiallyUpdatesRecords;
use Zbiller\CrudhubCms\Exceptions\MenuException;
use Zbiller\CrudhubCms\Requests\MenuRequest;
use Zbiller\CrudhubCms\Services\TreeService;

class MenuController
{
    use AuthorizesRequests;
    use ValidatesRequests;
    use BulkDestroysRecords;
    use PartiallyUpdatesRecords;

    /**
     * @param Request $request
     * @param MenuFilterContract $filter
     * @param MenuSortContract $sort
     * @return RedirectResponse|Response
     */
    public function index(Request $request, MenuFilterContract $filter, MenuSortContract $sort)
    {
        if (!$request->filled('location')) {
            return Inertia::render('Menus/Locations', [
                'options' => [
                    'locations' => $this->getLocations(),
                ],
            ]);
        }

        $location = $request->get('location');
        $query = App::make(MenuModelContract::class)->where('location', $location);
        $filters = $request->all();

        unset($filters['location']);
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

        return Inertia::render('Menus/Index', [
            'items' => Resource::collection('menu_resource', $items, 'crudhub-cms'),
            'options' => [
                'tree' => (new TreeService(App::make(MenuModelContract::class)))->getTree(['location' => $location]),
                'location' => $location,
                'locations' => $this->getLocations(),
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
        $parent = App::make(MenuModelContract::class)->find($parent);

        return Inertia::render('Menus/Create', [
            'parent' => $parent instanceof MenuModelContract && $parent->exists ? Resource::make('menu_resource', $parent, 'crudhub-cms') : null,
            'options' => [
                'locations' => $this->getLocations(),
                'types' => $this->getTypes(),
                'routes' => $this->getRoutes(),
            ],
        ]);
    }

    /**
     * @return \Illuminate\Http\RedirectResponse|void
     */
    public function store()
    {
        /** @var MenuRequest $request */
        $request = $this->initRequest();

        try {
            DB::beginTransaction();

            $menu = App::make(MenuModelContract::class)->create($request->all());

            DB::commit();

            Flash::success('Record created successfully!');

            return Redirect::saved(
                $request,
                route('admin.menus.index', ['location' => $menu->location]),
                route('admin.menus.edit', $menu->getKey())
            );
        } catch (Throwable $e) {
            DB::rollBack();

            Flash::error(exception: $e);
        }
    }

    /**
     * @param MenuModelContract $menu
     * @return Response
     */
    public function edit(MenuModelContract $menu)
    {
        $parent = App::make(MenuModelContract::class)->find($menu->parent_id);

        return Inertia::render('Menus/Edit', [
            'item' => Resource::make('menu_resource', $menu, 'crudhub-cms'),
            'parent' => $parent instanceof MenuModelContract && $parent->exists ? Resource::make('menu_resource', $parent, 'crudhub-cms') : null,
            'options' => [
                'locations' => $this->getLocations(),
                'types' => $this->getTypes(),
                'routes' => $this->getRoutes(),
            ],
        ]);
    }

    /**
     * @param MenuModelContract $menu
     * @return RedirectResponse|void
     */
    public function update(MenuModelContract $menu)
    {
        /** @var MenuRequest $request */
        $request = $this->initRequest();

        try {
            DB::beginTransaction();

            $menu->update($request->all());

            DB::commit();

            Flash::success('Record updated successfully!');

            return Redirect::saved(
                $request,
                route('admin.menus.index', ['location' => $menu->location]),
                route('admin.menus.edit', $menu->getKey())
            );
        } catch (MenuException $e) {
            DB::rollBack();

            Flash::error($e->getMessage(), $e);
        } catch (Throwable $e) {
            DB::rollBack();

            Flash::error(exception: $e);
        }
    }

    /**
     * @param Request $request
     * @param MenuModelContract $menu
     * @return RedirectResponse|void
     */
    public function destroy(Request $request, MenuModelContract $menu)
    {
        $location = $menu->location;

        try {
            DB::beginTransaction();

            $menu->delete();

            DB::commit();

            Flash::success('Record deleted successfully!');

            return Redirect::deleted(route('admin.menus.index'));
        } catch (MenuException $e) {
            DB::rollBack();

            Flash::error($e->getMessage(), $e);
        } catch (Throwable $e) {
            DB::rollBack();

            Flash::error(exception: $e);
        }
    }

    /**
     * @param string $type
     * @return JsonResponse
     */
    public function menuables(string $type): JsonResponse
    {
        $class = config("crudhub-cms.menus.types.$type");
        $items = App::make($class)->withoutGlobalScopes()->latest()->get();
        $records = [];

        foreach ($items as $item) {
            $records[] = [
                'value' => $item->getMenuableId(),
                'label' => $item->getMenuableName(),
            ];
        }

        return response()->json($records);
    }

    /**
     * @return string
     */
    public function bulkDestroyModel(): string
    {
        return MenuModelContract::class;
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
        return MenuModelContract::class;
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
        $request = config('crudhub-cms.bindings.form_requests.menu_form_request', MenuRequest::class);

        return App::make($request)->merged();
    }

    /**
     * @return array
     */
    protected function getLocations(): array
    {
        $locations = [];

        foreach ((array)config('crudhub-cms.menus.locations', []) as $location) {
            $locations[] = [
                'value' => $location,
                'label' => Str::title(str_replace(['_', '-', '.'], ' ', $location)),
            ];
        }

        return $locations;
    }

    /**
     * @return string[]
     */
    protected function getTypes(): array
    {
        $types = [
            [
                'value' => 'url',
                'label' => 'URL',
            ],
            [
                'value' => 'route',
                'label' => 'Route',
            ],
        ];

        foreach (array_keys((array)config('crudhub-cms.menus.types', [])) as $type) {
            $types[] = [
                'value' => $type,
                'label' => Str::title(str_replace(['_', '-', '.'], ' ', $type)),
            ];
        }

        return $types;
    }

    /**
     * @return array
     */
    protected function getRoutes(): array
    {
        $routes = [];

        foreach (get_menuable_routes() as $route) {
            $routes[] = [
                'value' => $route->getName(),
                'label' => $route->getName(),
            ];
        }

        return $routes;
    }
}
