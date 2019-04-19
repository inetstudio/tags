<?php

namespace InetStudio\TagsPackage\Tags\Providers;

use Collective\Html\FormBuilder;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;

/**
 * Class ServiceProvider.
 */
class ServiceProvider extends BaseServiceProvider
{
    /**
     * Загрузка сервиса.
     */
    public function boot(): void
    {
        $this->registerConsoleCommands();
        $this->registerPublishes();
        $this->registerRoutes();
        $this->registerViews();
        $this->registerFormComponents();
    }

    /**
     * Регистрация команд.
     */
    protected function registerConsoleCommands(): void
    {
        if (! $this->app->runningInConsole()) {
            return;
        }

        $this->commands(
            [
                'InetStudio\TagsPackage\Tags\Console\Commands\SetupCommand',
                'InetStudio\TagsPackage\Tags\Console\Commands\CreateFoldersCommand',
            ]
        );
    }

    /**
     * Регистрация ресурсов.
     */
    protected function registerPublishes(): void
    {
        $this->publishes(
            [
                __DIR__.'/../../config/tags.php' => config_path('tags.php'),
            ],
            'config'
        );

        $this->mergeConfigFrom(__DIR__.'/../../config/filesystems.php', 'filesystems.disks');

        if (! $this->app->runningInConsole()) {
            return;
        }

        if (Schema::hasTable('tags')) {
            return;
        }

        $timestamp = date('Y_m_d_His', time());
        $this->publishes(
            [
                __DIR__.'/../../database/migrations/create_tags_tables.php.stub' => database_path(
                    'migrations/'.$timestamp.'_create_tags_tables.php'
                ),
            ],
            'migrations'
        );
    }

    /**
     * Регистрация путей.
     */
    protected function registerRoutes(): void
    {
        $this->loadRoutesFrom(__DIR__.'/../../routes/web.php');
    }

    /**
     * Регистрация представлений.
     */
    protected function registerViews(): void
    {
        $this->loadViewsFrom(__DIR__.'/../../resources/views', 'admin.module.tags');
    }

    /**
     * Регистрация компонентов форм.
     */
    protected function registerFormComponents(): void
    {
        FormBuilder::component(
            'tags',
            'admin.module.tags::back.forms.fields.tags',
            ['name' => null, 'value' => null, 'attributes' => null]
        );
    }
}
