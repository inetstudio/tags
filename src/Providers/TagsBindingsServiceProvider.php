<?php

namespace InetStudio\Tags\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\Support\DeferrableProvider;

/**
 * Class TagsBindingsServiceProvider.
 */
class TagsBindingsServiceProvider extends ServiceProvider implements DeferrableProvider
{
    /**
    * @var  array
    */
    public $bindings = [
        'InetStudio\Tags\Contracts\Events\Back\ModifyTagEventContract' => 'InetStudio\Tags\Events\Back\ModifyTagEvent',
        'InetStudio\Tags\Contracts\Http\Controllers\Back\TagsControllerContract' => 'InetStudio\Tags\Http\Controllers\Back\TagsController',
        'InetStudio\Tags\Contracts\Http\Controllers\Back\TagsDataControllerContract' => 'InetStudio\Tags\Http\Controllers\Back\TagsDataController',
        'InetStudio\Tags\Contracts\Http\Controllers\Back\TagsUtilityControllerContract' => 'InetStudio\Tags\Http\Controllers\Back\TagsUtilityController',
        'InetStudio\Tags\Contracts\Http\Requests\Back\SaveTagRequestContract' => 'InetStudio\Tags\Http\Requests\Back\SaveTagRequest',
        'InetStudio\Tags\Contracts\Http\Responses\Back\Tags\DestroyResponseContract' => 'InetStudio\Tags\Http\Responses\Back\Tags\DestroyResponse',
        'InetStudio\Tags\Contracts\Http\Responses\Back\Tags\FormResponseContract' => 'InetStudio\Tags\Http\Responses\Back\Tags\FormResponse',
        'InetStudio\Tags\Contracts\Http\Responses\Back\Tags\IndexResponseContract' => 'InetStudio\Tags\Http\Responses\Back\Tags\IndexResponse',
        'InetStudio\Tags\Contracts\Http\Responses\Back\Tags\SaveResponseContract' => 'InetStudio\Tags\Http\Responses\Back\Tags\SaveResponse',
        'InetStudio\Tags\Contracts\Http\Responses\Back\Utility\SlugResponseContract' => 'InetStudio\Tags\Http\Responses\Back\Utility\SlugResponse',
        'InetStudio\Tags\Contracts\Http\Responses\Back\Utility\SuggestionsResponseContract' => 'InetStudio\Tags\Http\Responses\Back\Utility\SuggestionsResponse',
        'InetStudio\Tags\Contracts\Models\TaggableModelContract' => 'InetStudio\Tags\Models\TaggableModel',
        'InetStudio\Tags\Contracts\Models\TagModelContract' => 'InetStudio\Tags\Models\TagModel',
        'InetStudio\Tags\Contracts\Repositories\TagsRepositoryContract' => 'InetStudio\Tags\Repositories\TagsRepository',
        'InetStudio\Tags\Contracts\Services\Back\TagsDataTableServiceContract' => 'InetStudio\Tags\Services\Back\TagsDataTableService',
        'InetStudio\Tags\Contracts\Services\Back\TagsServiceContract' => 'InetStudio\Tags\Services\Back\TagsService',
        'InetStudio\Tags\Contracts\Services\Front\TagsServiceContract' => 'InetStudio\Tags\Services\Front\TagsService',
        'InetStudio\Tags\Contracts\Transformers\Back\SuggestionTransformerContract' => 'InetStudio\Tags\Transformers\Back\SuggestionTransformer',
        'InetStudio\Tags\Contracts\Transformers\Back\TagTransformerContract' => 'InetStudio\Tags\Transformers\Back\TagTransformer',
        'InetStudio\Tags\Contracts\Transformers\Front\TagsSiteMapTransformerContract' => 'InetStudio\Tags\Transformers\Front\TagsSiteMapTransformer',
    ];

    /**
     * Получить сервисы от провайдера.
     *
     * @return  array
     */
    public function provides()
    {
        return array_keys($this->bindings);
    }
}
