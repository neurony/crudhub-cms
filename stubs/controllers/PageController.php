<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Zbiller\CrudhubCms\Contracts\PageModelContract;

class PageController extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    /**
     * @var PageModelContract|null
     */
    protected ?PageModelContract $page;

    /**
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->page = $request->route()->action['page'] ?? null;

        if (!($this->page && $this->page->exists) || !$this->page->active) {
            abort(404);
        }
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function home()
    {
        return view('crudhub::pages.home')->with([
            'page' => $this->page,
        ]);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function show()
    {
        return view('crudhub::pages.default')->with([
            'page' => $this->page,
        ]);
    }
}
