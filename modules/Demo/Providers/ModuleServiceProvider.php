<?php

namespace Modules\Demo\Providers;

use App\Providers\EventServiceProvider;
use Modules\Base\Provider\BaseServiceProvider;
//{@hidden
use Modules\Demo\Consoles\SampleCmd;
//@hidden}

/**
 * notes: 注册模块依赖配置 - 必须
 */
class ModuleServiceProvider extends BaseServiceProvider
{
    /**
     * @var string $moduleName
     */
    protected $moduleName = 'Demo';

    /**
     * @var string $moduleNameLower
     */
    protected $moduleNameLower = 'demo';

    /**
     * Boot the application events.
     * @return void
     */
    public function boot()
    {
        $this->validatorBase();
        //$this->registerTranslations();
        //$this->registerConfig();
        //$this->registerViews();
        $this->loadMigrationsFrom(module_path($this->moduleName, 'Database/Migrations'));

        //{@hidden
        $this->commands([
            SampleCmd::class
        ]);
        //@hidden}

    }

    /**
     * Register the service provider.
     * @return void
     */
    public function register()
    {
        $this->app->register(RouteServiceProvider::class);
        $this->app->register(EventServiceProvider::class);
    }

    /**
     * Register config.
     * @return void
     */
    protected function registerConfig()
    {
        $this->publishes([
            module_path($this->moduleName, 'Config/config.php') => config_path($this->moduleNameLower . '.php'),
        ], 'config');
        $this->mergeConfigFrom(
            module_path($this->moduleName, 'Config/config.php'), $this->moduleNameLower
        );
    }

    /**
     * Register views.
     * @return void
     */
    public function registerViews()
    {
        $viewPath = resource_path('views/modules/' . $this->moduleNameLower);

        $sourcePath = module_path($this->moduleName, 'Resources/views');

        $this->publishes([
            $sourcePath => $viewPath
        ]);

        $this->loadViewsFrom(array_merge($this->getPublishableViewPaths(), [$sourcePath]), $this->moduleNameLower);
    }

    /**
     * Register translations.
     * @return void
     */
    public function registerTranslations()
    {
        $langPath = resource_path('lang/modules/' . $this->moduleNameLower);

        if (is_dir($langPath)) {
            $this->loadTranslationsFrom($langPath, $this->moduleNameLower);
        } else {
            $this->loadTranslationsFrom(module_path($this->moduleName, 'Resources/lang'), $this->moduleNameLower);
        }
    }

    /**
     * Get the services provided by the provider.
     * @return array
     */
    public function provides()
    {
        return [];
    }

    private function getPublishableViewPaths(): array
    {
        $paths = [];
        foreach (\Config::get('view.paths') as $path) {
            if (is_dir($path . '/modules/' . $this->moduleNameLower)) {
                $paths[] = $path . '/modules/' . $this->moduleNameLower;
            }
        }
        return $paths;
    }
}
