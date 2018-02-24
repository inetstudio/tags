<?php

Route::group([
    'namespace' => 'InetStudio\Tags\Contracts\Http\Controllers\Back',
    'middleware' => ['web', 'back.auth'],
    'prefix' => 'back',
], function () {
    Route::any('tags/data', 'TagsDataControllerContract@data')->name('back.tags.data.index');
    Route::post('tags/slug', 'TagsUtilityControllerContract@getSlug')->name('back.tags.getSlug');
    Route::post('tags/suggestions', 'TagsUtilityControllerContract@getSuggestions')->name('back.tags.getSuggestions');

    Route::resource('tags', 'TagsControllerContract', ['except' => [
        'show',
    ], 'as' => 'back']);
});
