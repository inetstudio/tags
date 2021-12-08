<?php

namespace InetStudio\TagsPackage\Tags\Providers;

use Collective\Html\FormBuilder;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;

class ServiceProvider extends BaseServiceProvider
{
    public function boot(): void
    {
        $this->registerConsoleCommands();
        $this->registerPublishes();
        $this->registerRoutes();
        $this->registerViews();
        $this->registerFormComponents();
    }

    protected function registerConsoleCommands(): void
    {
        if (! $this->app->runningInConsole()) {
            return;
        }

        $this->commands(
            [
                'InetStudio\TagsPackage\Tags\Console\Commands\CreateFoldersCommand',
                'InetStudio\TagsPackage\Tags\Console\Commands\CreatePermissionsCommand',
                'InetStudio\TagsPackage\Tags\Console\Commands\SetupCommand',
            ]
        );
    }

    protected function registerPublishes(): void
    {
        $this->publishes(
            [
                __DIR__.'/../../config/tags_package_tags.php' => config_path('tags_package_tags.php'),
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
                __DIR__.'/../../database/migrations/create_tags_package_tables.php.stub' => database_path(
                    'migrations/'.$timestamp.'_create_tags_package_tables.php'
                ),
            ],
            'migrations'
        );
    }

    protected function registerRoutes(): void
    {
        $this->loadRoutesFrom(__DIR__.'/../../routes/web.php');
    }

    protected function registerViews(): void
    {
        $this->loadViewsFrom(__DIR__.'/../../resources/views', 'admin.module.tags-package.tags');
    }

    protected function registerFormComponents(): void
    {
        FormBuilder::component(
            'tags-package.tags',
            'admin.module.tags-package.tags::back.forms.fields.tags',
            ['name' => null, 'value' => null, 'attributes' => null]
        );
    }
}
