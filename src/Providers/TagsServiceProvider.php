<?php

namespace InetStudio\Tags\Providers;

use Illuminate\Support\ServiceProvider;

/**
 * Class TagsServiceProvider.
 */
class TagsServiceProvider extends ServiceProvider
{
    /**
     * Загрузка сервиса.
     *
     * @return void
     */
    public function boot(): void
    {
        $this->registerConsoleCommands();
        $this->registerPublishes();
        $this->registerRoutes();
        $this->registerViews();
        $this->registerObservers();
    }

    /**
     * Регистрация привязки в контейнере.
     *
     * @return void
     */
    public function register(): void
    {
        $this->registerBindings();
    }

    /**
     * Регистрация команд.
     *
     * @return void
     */
    protected function registerConsoleCommands(): void
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                'InetStudio\Tags\Console\Commands\SetupCommand',
                'InetStudio\Tags\Console\Commands\CreateFoldersCommand',
            ]);
        }
    }

    /**
     * Регистрация ресурсов.
     *
     * @return void
     */
    protected function registerPublishes(): void
    {
        $this->publishes([
            __DIR__.'/../../config/tags.php' => config_path('tags.php'),
        ], 'config');

        $this->mergeConfigFrom(
            __DIR__.'/../../config/filesystems.php', 'filesystems.disks'
        );

        if ($this->app->runningInConsole()) {
            if (! class_exists('CreateTagsTables')) {
                $timestamp = date('Y_m_d_His', time());
                $this->publishes([
                    __DIR__.'/../../database/migrations/create_tags_tables.php.stub' => database_path('migrations/'.$timestamp.'_create_tags_tables.php'),
                ], 'migrations');
            }
        }
    }

    /**
     * Регистрация путей.
     *
     * @return void
     */
    protected function registerRoutes(): void
    {
        $this->loadRoutesFrom(__DIR__.'/../../routes/web.php');
    }

    /**
     * Регистрация представлений.
     *
     * @return void
     */
    protected function registerViews(): void
    {
        $this->loadViewsFrom(__DIR__.'/../../resources/views', 'admin.module.tags');
    }

    /**
     * Регистрация наблюдателей.
     *
     * @return void
     */
    public function registerObservers(): void
    {
        $this->app->make('InetStudio\Tags\Contracts\Models\TagModelContract')::observe($this->app->make('InetStudio\Tags\Contracts\Observers\TagObserverContract'));
    }

    /**
     * Регистрация привязок, алиасов и сторонних провайдеров сервисов.
     *
     * @return void
     */
    public function registerBindings(): void
    {
        // Controllers
        $this->app->bind('InetStudio\Tags\Contracts\Http\Controllers\Back\TagsControllerContract', 'InetStudio\Tags\Http\Controllers\Back\TagsController');
        $this->app->bind('InetStudio\Tags\Contracts\Http\Controllers\Back\TagsDataControllerContract', 'InetStudio\Tags\Http\Controllers\Back\TagsDataController');
        $this->app->bind('InetStudio\Tags\Contracts\Http\Controllers\Back\TagsUtilityControllerContract', 'InetStudio\Tags\Http\Controllers\Back\TagsUtilityController');

        // Events
        $this->app->bind('InetStudio\Tags\Contracts\Events\Back\ModifyTagEventContract', 'InetStudio\Tags\Events\Back\ModifyTagEvent');

        // Models
        $this->app->bind('InetStudio\Tags\Contracts\Models\TagModelContract', 'InetStudio\Tags\Models\TagModel');
        $this->app->bind('InetStudio\Tags\Contracts\Models\TaggableModelContract', 'InetStudio\Tags\Models\TaggableModel');

        // Observers
        $this->app->bind('InetStudio\Tags\Contracts\Observers\TagObserverContract', 'InetStudio\Tags\Observers\TagObserver');

        // Repositories
        $this->app->bind('InetStudio\Tags\Contracts\Repositories\TagsRepositoryContract', 'InetStudio\Tags\Repositories\TagsRepository');

        // Requests
        $this->app->bind('InetStudio\Tags\Contracts\Http\Requests\Back\SaveTagRequestContract', 'InetStudio\Tags\Http\Requests\Back\SaveTagRequest');

        // Responses
        $this->app->bind('InetStudio\Tags\Contracts\Http\Responses\Back\Tags\DestroyResponseContract', 'InetStudio\Tags\Http\Responses\Back\Tags\DestroyResponse');
        $this->app->bind('InetStudio\Tags\Contracts\Http\Responses\Back\Tags\FormResponseContract', 'InetStudio\Tags\Http\Responses\Back\Tags\FormResponse');
        $this->app->bind('InetStudio\Tags\Contracts\Http\Responses\Back\Tags\IndexResponseContract', 'InetStudio\Tags\Http\Responses\Back\Tags\IndexResponse');
        $this->app->bind('InetStudio\Tags\Contracts\Http\Responses\Back\Tags\SaveResponseContract', 'InetStudio\Tags\Http\Responses\Back\Tags\SaveResponse');
        $this->app->bind('InetStudio\Tags\Contracts\Http\Responses\Back\Utility\SlugResponseContract', 'InetStudio\Tags\Http\Responses\Back\Utility\SlugResponse');
        $this->app->bind('InetStudio\Tags\Contracts\Http\Responses\Back\Utility\SuggestionsResponseContract', 'InetStudio\Tags\Http\Responses\Back\Utility\SuggestionsResponse');

        // Services
        $this->app->bind('InetStudio\Tags\Contracts\Services\Back\TagsDataTableServiceContract', 'InetStudio\Tags\Services\Back\TagsDataTableService');
        $this->app->bind('InetStudio\Tags\Contracts\Services\Back\TagsObserverServiceContract', 'InetStudio\Tags\Services\Back\TagsObserverService');
        $this->app->bind('InetStudio\Tags\Contracts\Services\Back\TagsServiceContract', 'InetStudio\Tags\Services\Back\TagsService');
        $this->app->bind('InetStudio\Tags\Contracts\Services\Front\TagsServiceContract', 'InetStudio\Tags\Services\Front\TagsService');

        // Transformers
        $this->app->bind('InetStudio\Tags\Contracts\Transformers\Back\TagTransformerContract', 'InetStudio\Tags\Transformers\Back\TagTransformer');
        $this->app->bind('InetStudio\Tags\Contracts\Transformers\Back\SuggestionTransformerContract', 'InetStudio\Tags\Transformers\Back\SuggestionTransformer');
        $this->app->bind('InetStudio\Tags\Contracts\Transformers\Front\TagsSiteMapTransformerContract', 'InetStudio\Tags\Transformers\Front\TagsSiteMapTransformer');
    }
}
