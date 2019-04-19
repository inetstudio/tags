<?php

namespace InetStudio\TagsPackage\Tags\Providers;

use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;

/**
 * Class BindingsServiceProvider.
 */
class BindingsServiceProvider extends BaseServiceProvider implements DeferrableProvider
{
    /**
     * @var array
     */
    public $bindings = [
        'InetStudio\TagsPackage\Tags\Contracts\Events\Back\ModifyItemEventContract' => 'InetStudio\TagsPackage\Tags\Events\Back\ModifyItemEvent',
        'InetStudio\TagsPackage\Tags\Contracts\Http\Controllers\Back\ResourceControllerContract' => 'InetStudio\TagsPackage\Tags\Http\Controllers\Back\ResourceController',
        'InetStudio\TagsPackage\Tags\Contracts\Http\Controllers\Back\DataControllerContract' => 'InetStudio\TagsPackage\Tags\Http\Controllers\Back\DataController',
        'InetStudio\TagsPackage\Tags\Contracts\Http\Controllers\Back\UtilityControllerContract' => 'InetStudio\TagsPackage\Tags\Http\Controllers\Back\UtilityController',
        'InetStudio\TagsPackage\Tags\Contracts\Http\Requests\Back\SaveItemRequestContract' => 'InetStudio\TagsPackage\Tags\Http\Requests\Back\SaveItemRequest',
        'InetStudio\TagsPackage\Tags\Contracts\Http\Responses\Back\Resource\DestroyResponseContract' => 'InetStudio\TagsPackage\Tags\Http\Responses\Back\Resource\DestroyResponse',
        'InetStudio\TagsPackage\Tags\Contracts\Http\Responses\Back\Resource\FormResponseContract' => 'InetStudio\TagsPackage\Tags\Http\Responses\Back\Resource\FormResponse',
        'InetStudio\TagsPackage\Tags\Contracts\Http\Responses\Back\Resource\IndexResponseContract' => 'InetStudio\TagsPackage\Tags\Http\Responses\Back\Resource\IndexResponse',
        'InetStudio\TagsPackage\Tags\Contracts\Http\Responses\Back\Resource\SaveResponseContract' => 'InetStudio\TagsPackage\Tags\Http\Responses\Back\Resource\SaveResponse',
        'InetStudio\TagsPackage\Tags\Contracts\Http\Responses\Back\Utility\SlugResponseContract' => 'InetStudio\TagsPackage\Tags\Http\Responses\Back\Utility\SlugResponse',
        'InetStudio\TagsPackage\Tags\Contracts\Http\Responses\Back\Utility\SuggestionsResponseContract' => 'InetStudio\TagsPackage\Tags\Http\Responses\Back\Utility\SuggestionsResponse',
        'InetStudio\TagsPackage\Tags\Contracts\Models\TaggableModelContract' => 'InetStudio\TagsPackage\Tags\Models\TaggableModel',
        'InetStudio\TagsPackage\Tags\Contracts\Models\TagModelContract' => 'InetStudio\TagsPackage\Tags\Models\TagModel',
        'InetStudio\TagsPackage\Tags\Contracts\Services\Back\DataTableServiceContract' => 'InetStudio\TagsPackage\Tags\Services\Back\DataTableService',
        'InetStudio\TagsPackage\Tags\Contracts\Services\Back\ItemsServiceContract' => 'InetStudio\TagsPackage\Tags\Services\Back\ItemsService',
        'InetStudio\TagsPackage\Tags\Contracts\Services\Back\UtilityServiceContract' => 'InetStudio\TagsPackage\Tags\Services\Back\UtilityService',
        'InetStudio\TagsPackage\Tags\Contracts\Services\Front\ItemsServiceContract' => 'InetStudio\TagsPackage\Tags\Services\Front\ItemsService',
        'InetStudio\TagsPackage\Tags\Contracts\Services\Front\SitemapServiceContract' => 'InetStudio\TagsPackage\Tags\Services\Front\SitemapService',
        'InetStudio\TagsPackage\Tags\Contracts\Transformers\Back\Utility\SuggestionTransformerContract' => 'InetStudio\TagsPackage\Tags\Transformers\Back\Utility\SuggestionTransformer',
        'InetStudio\TagsPackage\Tags\Contracts\Transformers\Back\Resource\IndexTransformerContract' => 'InetStudio\TagsPackage\Tags\Transformers\Back\Resource\IndexTransformer',
        'InetStudio\TagsPackage\Tags\Contracts\Transformers\Front\Sitemap\ItemTransformerContract' => 'InetStudio\TagsPackage\Tags\Transformers\Front\Sitemap\ItemTransformer',
    ];

    /**
     * Получить сервисы от провайдера.
     *
     * @return array
     */
    public function provides()
    {
        return array_keys($this->bindings);
    }
}
