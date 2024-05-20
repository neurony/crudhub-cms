<?php

namespace Zbiller\CrudhubCms;

use Illuminate\Config\Repository as ConfigRepository;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;
use Zbiller\CrudhubCms\Commands\InstallCommand;
use Zbiller\CrudhubCms\Contracts\BlockFilterContract;
use Zbiller\CrudhubCms\Contracts\BlockModelContract;
use Zbiller\CrudhubCms\Contracts\BlockSortContract;
use Zbiller\CrudhubCms\Contracts\MenuFilterContract;
use Zbiller\CrudhubCms\Contracts\MenuModelContract;
use Zbiller\CrudhubCms\Contracts\MenuSortContract;
use Zbiller\CrudhubCms\Contracts\PageFilterContract;
use Zbiller\CrudhubCms\Contracts\PageModelContract;
use Zbiller\CrudhubCms\Contracts\PageSortContract;
use Zbiller\CrudhubCms\Contracts\TreeServiceContract;
use Zbiller\CrudhubCms\Filters\BlockFilter;
use Zbiller\CrudhubCms\Filters\MenuFilter;
use Zbiller\CrudhubCms\Filters\PageFilter;
use Zbiller\CrudhubCms\Models\Block;
use Zbiller\CrudhubCms\Models\Menu;
use Zbiller\CrudhubCms\Models\Page;
use Zbiller\CrudhubCms\Services\TreeService;
use Zbiller\CrudhubCms\Sorts\BlockSort;
use Zbiller\CrudhubCms\Sorts\MenuSort;
use Zbiller\CrudhubCms\Sorts\PageSort;

class CrudhubCmsServiceProvider extends BaseServiceProvider
{
    /**
     * @var ConfigRepository
     */
    protected ConfigRepository $config;

    /**
     * @param Application $app
     */
    public function __construct(Application $app)
    {
        parent::__construct($app);

        $this->config = $this->app->config;
    }

    /**
     * @param Router $router
     * @return void
     */
    public function boot(Router $router)
    {
        Schema::defaultStringLength(125);

        $this->publishConfigs();
        $this->publishViews();
        $this->publishMigrations();
        $this->publishSeeders();
        $this->registerCommands();
        $this->registerRouteBindings();
        $this->registerRoutes();
        $this->loadViews();
    }

    /**
     * @return void
     */
    public function register()
    {
        $this->mergeConfigs();
        $this->registerModelBindings();
        $this->registerServiceBindings();
        $this->registerFilterBindings();
        $this->registerSortBindings();
    }

    /**
     * @return void
     */
    protected function publishConfigs()
    {
        $this->publishes([
            __DIR__ . '/../config/bindings.php' => config_path('crudhub-cms/bindings.php'),
            __DIR__ . '/../config/blocks.php' => config_path('crudhub-cms/blocks.php'),
            __DIR__ . '/../config/menus.php' => config_path('crudhub-cms/menus.php'),
            __DIR__ . '/../config/pages.php' => config_path('crudhub-cms/pages.php'),
            __DIR__ . '/../config/tinymce.php' => config_path('crudhub-cms/tinymce.php'),
        ], 'crudhub-cms-config');
    }

    /**
     * @return void
     */
    protected function publishViews()
    {
        $this->publishes([
            __DIR__ . '/../resources/views' => resource_path('views/vendor/crudhub'),
        ], 'crudhub-cms-views');
    }

    /**
     * @return void
     */
    protected function publishMigrations()
    {
        if (empty(File::glob(database_path('migrations/*_create_crudhub_cms_tables.php')))) {
            $timestamp = date('Y_m_d_His', time() + 60);

            $this->publishes([
                __DIR__ . '/../database/migrations/create_crudhub_cms_tables.php' => database_path() . "/migrations/{$timestamp}_create_crudhub_cms_tables.php",
            ], 'crudhub-cms-migrations');
        }
    }

    /**
     * @return void
     */
    protected function publishSeeders()
    {
        $this->publishes([
            __DIR__ . '/../database/seeders' => database_path('seeders/CrudhubCms'),
        ], 'crudhub-cms-seeders');
    }

    /**
     * @return void
     */
    protected function registerCommands()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                InstallCommand::class,
            ]);
        }
    }

    /**
     * @return void
     */
    protected function registerRouteBindings()
    {
        Route::model('page', PageModelContract::class);
        Route::model('menu', MenuModelContract::class);
        Route::model('block', BlockModelContract::class);
    }

    /**
     * @return void
     */
    protected function registerRoutes()
    {
        Route::macro('crudhubCms', function () {
            require __DIR__ . '/../routes/routes.php';
        });
    }

    /**
     * @return void
     */
    protected function loadViews()
    {
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'crudhub');
    }

    /**
     * @return void
     */
    protected function mergeConfigs()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/bindings.php', 'crudhub-cms.bindings');
        $this->mergeConfigFrom(__DIR__ . '/../config/blocks.php', 'crudhub-cms.blocks');
        $this->mergeConfigFrom(__DIR__ . '/../config/menus.php', 'crudhub-cms.menus');
        $this->mergeConfigFrom(__DIR__ . '/../config/pages.php', 'crudhub-cms.pages');
        $this->mergeConfigFrom(__DIR__ . '/../config/tinymce.php', 'crudhub-cms.tinymce');
    }

    /**
     * @return void
     */
    protected function registerModelBindings()
    {
        $binding = $this->config['crudhub-cms.bindings.models'];

        $this->app->bind(PageModelContract::class, $binding['page_model'] ?? Page::class);
        $this->app->bind(MenuModelContract::class, $binding['menu_model'] ?? Menu::class);
        $this->app->bind(BlockModelContract::class, $binding['menu_model'] ?? Block::class);
    }

    /**
     * @return void
     */
    protected function registerServiceBindings()
    {
        $binding = $this->config['crudhub-cms.bindings.services'];

        $this->app->bind(TreeServiceContract::class, $binding['tree_service'] ?? TreeService::class);
    }

    /**
     * @return void
     */
    protected function registerFilterBindings()
    {
        $binding = $this->config['crudhub-cms.bindings.filters'];

        $this->app->singleton(PageFilterContract::class, $binding['page_filter'] ?? PageFilter::class);
        $this->app->singleton(MenuFilterContract::class, $binding['menu_filter'] ?? MenuFilter::class);
        $this->app->singleton(BlockFilterContract::class, $binding['block_filter'] ?? BlockFilter::class);
    }

    /**
     * @return void
     */
    protected function registerSortBindings()
    {
        $binding = $this->config['crudhub-cms.bindings.sorts'];

        $this->app->singleton(PageSortContract::class, $binding['page_sort'] ?? PageSort::class);
        $this->app->singleton(MenuSortContract::class, $binding['menu_sort'] ?? MenuSort::class);
        $this->app->singleton(BlockSortContract::class, $binding['block_sort'] ?? BlockSort::class);
    }
}
