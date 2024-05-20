<?php

use Illuminate\Support\Facades\Route;
use Zbiller\CrudhubCms\Controllers\MenuTreeController;
use Zbiller\CrudhubCms\Controllers\PageController;
use Zbiller\CrudhubCms\Controllers\PageTreeController;
use Zbiller\CrudhubCms\Controllers\MenuController;
use Zbiller\CrudhubCms\Controllers\BlockController;

$prefix = config('crudhub.admin.prefix', 'admin');

$controllers = [
    'page' => '\\' . config('crudhub-cms.bindings.controllers.page_controller', PageController::class),
    'page_tree' => '\\' . config('crudhub-cms.bindings.controllers.page_tree_controller', PageTreeController::class),
    'menu' => '\\' . config('crudhub-cms.bindings.controllers.menu_controller', MenuController::class),
    'menu_tree' => '\\' . config('crudhub-cms.bindings.controllers.menu_tree_controller', MenuTreeController::class),
    'block' => '\\' . config('crudhub-cms.bindings.controllers.block_controller', BlockController::class),
];

Route::prefix($prefix)->middleware([
    'web',
    'crudhub.inertia.handle_requests',
])->group(function () use ($controllers) {
    /**
     * Page routes
     */
    Route::prefix('pages')->middleware([
        'auth:admin',
    ])->group(function () use ($controllers) {
        Route::get('', [$controllers['page'], 'index'])->name('admin.pages.index')->middleware('permission:pages-list');
        Route::get('create/{parent?}', [$controllers['page'], 'create'])->name('admin.pages.create')->middleware('permission:pages-add');
        Route::post('', [$controllers['page'], 'store'])->name('admin.pages.store')->middleware('permission:pages-add');
        Route::get('{page}/edit', [$controllers['page'], 'edit'])->name('admin.pages.edit')->middleware('permission:pages-edit');
        Route::put('{page}', [$controllers['page'], 'update'])->name('admin.pages.update')->middleware('permission:pages-edit');
        Route::delete('{page}', [$controllers['page'], 'destroy'])->name('admin.pages.destroy')->middleware('permission:pages-delete');
        Route::patch('{id}', [$controllers['page'], 'partialUpdate'])->name('admin.pages.partial_update')->middleware('permission:pages-edit');
        Route::post('bulk-destroy', [$controllers['page'], 'bulkDestroy'])->name('admin.pages.bulk_destroy')->middleware('permission:pages-delete');

        Route::prefix('tree')->group(function () use ($controllers) {
            Route::post('fix', [$controllers['page_tree'], 'fix'])->name('admin.pages.tree.fix')->middleware('permission:pages-edit');
            Route::post('rebuild', [$controllers['page_tree'], 'rebuild'])->name('admin.pages.tree.rebuild')->middleware('permission:pages-edit');
        });
    });

    /**
     * Menu routes
     */
    Route::prefix('menus')->middleware([
        'auth:admin',
    ])->group(function () use ($controllers) {
        Route::get('', [$controllers['menu'], 'index'])->name('admin.menus.index')->middleware('permission:menus-list');
        Route::get('create/{parent?}', [$controllers['menu'], 'create'])->name('admin.menus.create')->middleware('permission:menus-add');
        Route::post('', [$controllers['menu'], 'store'])->name('admin.menus.store')->middleware('permission:menus-add');
        Route::get('{menu}/edit', [$controllers['menu'], 'edit'])->name('admin.menus.edit')->middleware('permission:menus-edit');
        Route::put('{menu}', [$controllers['menu'], 'update'])->name('admin.menus.update')->middleware('permission:menus-edit');
        Route::delete('/{menu}', [$controllers['menu'], 'destroy'])->name('admin.menus.destroy')->middleware('permission:menus-delete');
        Route::patch('{id}', [$controllers['menu'], 'partialUpdate'])->name('admin.menus.partial_update')->middleware('permission:menus-edit');
        Route::post('bulk-destroy', [$controllers['menu'], 'bulkDestroy'])->name('admin.menus.bulk_destroy')->middleware('permission:menus-delete');
        Route::get('menuables/{type}', [$controllers['menu'], 'menuables'])->name('admin.menus.menuables')->middleware('permission:menus-list');

        Route::prefix('tree')->group(function () use ($controllers) {
            Route::post('fix', [$controllers['menu_tree'], 'fix'])->name('admin.menus.tree.fix')->middleware('permission:menus-edit');
            Route::post('rebuild', [$controllers['menu_tree'], 'rebuild'])->name('admin.menus.tree.rebuild')->middleware('permission:menus-edit');
        });
    });

    /**
     * Block routes
     */
    Route::prefix('blocks')->middleware([
        'auth:admin',
    ])->group(function () use ($controllers) {
        Route::get('', [$controllers['block'], 'index'])->name('admin.blocks.index')->middleware('permission:blocks-list');
        Route::get('create', [$controllers['block'], 'create'])->name('admin.blocks.create')->middleware('permission:blocks-add');
        Route::post('', [$controllers['block'], 'store'])->name('admin.blocks.store')->middleware('permission:blocks-add');
        Route::get('{block}/edit', [$controllers['block'], 'edit'])->name('admin.blocks.edit')->middleware('permission:blocks-edit');
        Route::put('{block}', [$controllers['block'], 'update'])->name('admin.blocks.update')->middleware('permission:blocks-edit');
        Route::delete('{block}', [$controllers['block'], 'destroy'])->name('admin.blocks.destroy')->middleware('permission:blocks-delete');
        Route::patch('{id}', [$controllers['block'], 'partialUpdate'])->name('admin.blocks.partial_update')->middleware('permission:blocks-edit');
        Route::post('bulk-destroy', [$controllers['block'], 'bulkDestroy'])->name('admin.blocks.bulk_destroy')->middleware('permission:blocks-delete');
    });
});
