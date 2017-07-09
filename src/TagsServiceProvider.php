<?php

namespace InetStudio\Tags;

use Illuminate\Support\ServiceProvider;

class TagsServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->loadViewsFrom(__DIR__.'/../resources/views/admin', 'admin.module.tags');
        $this->loadRoutesFrom(__DIR__.'/../routes/web.php');

        if ($this->app->runningInConsole()) {
            if (! class_exists('CreateTagTables')) {
                $timestamp = date('Y_m_d_His', time());
                $this->publishes([
                    __DIR__.'/../database/migrations/create_tag_tables.php.stub' => database_path('migrations/'.$timestamp.'_create_tag_tables.php'),
                ], 'migrations');
            }
        }
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->register('Phoenix\EloquentMeta\ServiceProvider');
        $this->app->register('Cviebrock\EloquentSluggable\ServiceProvider');
    }
}
