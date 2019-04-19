<?php

Use Illuminate\Support\Facades\Route;

Route::group(
    [
        'namespace' => 'InetStudio\TagsPackage\Tags\Contracts\Http\Controllers\Back',
        'middleware' => ['web', 'back.auth'],
        'prefix' => 'back',
    ],
    function () {
        Route::any('tags/data', 'DataControllerContract@data')
            ->name('back.tags.data.index');

        Route::post('tags/slug', 'UtilityControllerContract@getSlug')
            ->name('back.tags.getSlug');

        Route::post('tags/suggestions', 'UtilityControllerContract@getSuggestions')
            ->name('back.tags.getSuggestions');

        Route::resource(
            'tags',
            'ResourceControllerContract',
            [
                'except' => [
                    'show',
                ],
                'as' => 'back',
            ]
        );
    }
);
