<?php

Route::group(['namespace' => 'InetStudio\Tags\Controllers'], function () {
    Route::group(['middleware' => 'web', 'prefix' => 'back'], function () {
        Route::group(['middleware' => 'back.auth'], function () {
            Route::post('tags/slug', 'TagsController@getSlug')->name('back.tags.getSlug');
            Route::post('tags/suggestions', 'TagsController@getSuggestions')->name('back.tags.getSuggestions');
            Route::resource('tags', 'TagsController', ['except' => [
                'show',
            ], 'as' => 'back']);
        });
    });
});
