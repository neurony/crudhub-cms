<?php

namespace Zbiller\CrudhubCms\Controllers;

use Illuminate\Support\Facades\App;
use Zbiller\Crudhub\Facades\Flash;
use Zbiller\CrudhubCms\Contracts\MenuModelContract;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;

class MenuTreeController
{
    use AuthorizesRequests;
    use ValidatesRequests;

    /**
     * @param Request $request
     * @return void
     */
    public function fix(Request $request): void
    {
        App::make(MenuModelContract::class)->fixTree($request->get('tree'));

        Flash::success('Tree fixed successfully');
    }

    /**
     * @param Request $request
     * @return void
     */
    public function rebuild(Request $request): void
    {
        App::make(MenuModelContract::class)->rebuildTree($request->get('tree'));
    }
}
