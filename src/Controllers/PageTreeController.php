<?php

namespace Zbiller\CrudhubCms\Controllers;

use Illuminate\Support\Facades\App;
use Zbiller\Crudhub\Facades\Flash;
use Zbiller\CrudhubCms\Contracts\PageModelContract;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;

class PageTreeController
{
    use AuthorizesRequests;
    use ValidatesRequests;

    /**
     * @param Request $request
     * @return void
     */
    public function fix(Request $request): void
    {
        App::make(PageModelContract::class)->fixTree($request->get('tree'));

        Flash::success('Tree fixed successfully');
    }

    /**
     * @param Request $request
     * @return void
     */
    public function rebuild(Request $request): void
    {
        App::make(PageModelContract::class)->rebuildTree($request->get('tree'));
    }
}
