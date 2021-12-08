<?php

namespace InetStudio\TagsPackage\Tags\Providers;

use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;

class BindingsServiceProvider extends BaseServiceProvider implements DeferrableProvider
{
    public array $bindings = [
        'InetStudio\TagsPackage\Tags\Contracts\DTO\Back\Resource\Save\ItemDataContract' => 'InetStudio\TagsPackage\Tags\DTO\Back\Resource\Save\ItemData',

        'InetStudio\TagsPackage\Tags\Contracts\Events\Back\ModifyItemEventContract' => 'InetStudio\TagsPackage\Tags\Events\Back\ModifyItemEvent',

        'InetStudio\TagsPackage\Tags\Contracts\Http\Controllers\Back\ResourceControllerContract' => 'InetStudio\TagsPackage\Tags\Http\Controllers\Back\ResourceController',
        'InetStudio\TagsPackage\Tags\Contracts\Http\Controllers\Back\DataControllerContract' => 'InetStudio\TagsPackage\Tags\Http\Controllers\Back\DataController',
        'InetStudio\TagsPackage\Tags\Contracts\Http\Controllers\Back\UtilityControllerContract' => 'InetStudio\TagsPackage\Tags\Http\Controllers\Back\UtilityController',

        'InetStudio\TagsPackage\Tags\Contracts\Http\Requests\Back\Data\GetIndexDataRequestContract' => 'InetStudio\TagsPackage\Tags\Http\Requests\Back\Data\GetIndexDataRequest',
        'InetStudio\TagsPackage\Tags\Contracts\Http\Requests\Back\Resource\CreateRequestContract' => 'InetStudio\TagsPackage\Tags\Http\Requests\Back\Resource\CreateRequest',
        'InetStudio\TagsPackage\Tags\Contracts\Http\Requests\Back\Resource\DestroyRequestContract' => 'InetStudio\TagsPackage\Tags\Http\Requests\Back\Resource\DestroyRequest',
        'InetStudio\TagsPackage\Tags\Contracts\Http\Requests\Back\Resource\EditRequestContract' => 'InetStudio\TagsPackage\Tags\Http\Requests\Back\Resource\EditRequest',
        'InetStudio\TagsPackage\Tags\Contracts\Http\Requests\Back\Resource\IndexRequestContract' => 'InetStudio\TagsPackage\Tags\Http\Requests\Back\Resource\IndexRequest',
        'InetStudio\TagsPackage\Tags\Contracts\Http\Requests\Back\Resource\ShowRequestContract' => 'InetStudio\TagsPackage\Tags\Http\Requests\Back\Resource\ShowRequest',
        'InetStudio\TagsPackage\Tags\Contracts\Http\Requests\Back\Resource\StoreRequestContract' => 'InetStudio\TagsPackage\Tags\Http\Requests\Back\Resource\StoreRequest',
        'InetStudio\TagsPackage\Tags\Contracts\Http\Requests\Back\Resource\UpdateRequestContract' => 'InetStudio\TagsPackage\Tags\Http\Requests\Back\Resource\UpdateRequest',
        'InetStudio\TagsPackage\Tags\Contracts\Http\Requests\Back\Utility\GetSuggestionsRequestContract' => 'InetStudio\TagsPackage\Tags\Http\Requests\Back\Utility\GetSuggestionsRequest',

        'InetStudio\TagsPackage\Tags\Contracts\Http\Resources\Back\Resource\Index\ItemResourceContract' => 'InetStudio\TagsPackage\Tags\Http\Resources\Back\Resource\Index\ItemResource',
        'InetStudio\TagsPackage\Tags\Contracts\Http\Resources\Back\Resource\Show\ItemResourceContract' => 'InetStudio\TagsPackage\Tags\Http\Resources\Back\Resource\Show\ItemResource',
        'InetStudio\TagsPackage\Tags\Contracts\Http\Resources\Back\Utility\Suggestions\AutocompleteItemResourceContract' => 'InetStudio\TagsPackage\Tags\Http\Resources\Back\Utility\Suggestions\AutocompleteItemResource',
        'InetStudio\TagsPackage\Tags\Contracts\Http\Resources\Back\Utility\Suggestions\ItemResourceContract' => 'InetStudio\TagsPackage\Tags\Http\Resources\Back\Utility\Suggestions\ItemResource',
        'InetStudio\TagsPackage\Tags\Contracts\Http\Resources\Back\Utility\Suggestions\ItemsCollectionContract' => 'InetStudio\TagsPackage\Tags\Http\Resources\Back\Utility\Suggestions\ItemsCollection',

        'InetStudio\TagsPackage\Tags\Contracts\Http\Responses\Back\Data\GetIndexDataResponseContract' => 'InetStudio\TagsPackage\Tags\Http\Responses\Back\Data\GetIndexDataResponse',
        'InetStudio\TagsPackage\Tags\Contracts\Http\Responses\Back\Resource\CreateResponseContract' => 'InetStudio\TagsPackage\Tags\Http\Responses\Back\Resource\CreateResponse',
        'InetStudio\TagsPackage\Tags\Contracts\Http\Responses\Back\Resource\DestroyResponseContract' => 'InetStudio\TagsPackage\Tags\Http\Responses\Back\Resource\DestroyResponse',
        'InetStudio\TagsPackage\Tags\Contracts\Http\Responses\Back\Resource\EditResponseContract' => 'InetStudio\TagsPackage\Tags\Http\Responses\Back\Resource\EditResponse',
        'InetStudio\TagsPackage\Tags\Contracts\Http\Responses\Back\Resource\IndexResponseContract' => 'InetStudio\TagsPackage\Tags\Http\Responses\Back\Resource\IndexResponse',
        'InetStudio\TagsPackage\Tags\Contracts\Http\Responses\Back\Resource\ShowResponseContract' => 'InetStudio\TagsPackage\Tags\Http\Responses\Back\Resource\ShowResponse',
        'InetStudio\TagsPackage\Tags\Contracts\Http\Responses\Back\Resource\StoreResponseContract' => 'InetStudio\TagsPackage\Tags\Http\Responses\Back\Resource\StoreResponse',
        'InetStudio\TagsPackage\Tags\Contracts\Http\Responses\Back\Resource\UpdateResponseContract' => 'InetStudio\TagsPackage\Tags\Http\Responses\Back\Resource\UpdateResponse',
        'InetStudio\TagsPackage\Tags\Contracts\Http\Responses\Back\Utility\GetSuggestionsResponseContract' => 'InetStudio\TagsPackage\Tags\Http\Responses\Back\Utility\GetSuggestionsResponse',

        'InetStudio\TagsPackage\Tags\Contracts\Models\TaggableModelContract' => 'InetStudio\TagsPackage\Tags\Models\TaggableModel',
        'InetStudio\TagsPackage\Tags\Contracts\Models\TagModelContract' => 'InetStudio\TagsPackage\Tags\Models\TagModel',

        'InetStudio\TagsPackage\Tags\Contracts\Services\Back\DataTables\IndexServiceContract' => 'InetStudio\TagsPackage\Tags\Services\Back\DataTables\IndexService',
        'InetStudio\TagsPackage\Tags\Contracts\Services\Back\ItemsServiceContract' => 'InetStudio\TagsPackage\Tags\Services\Back\ItemsService',
        'InetStudio\TagsPackage\Tags\Contracts\Services\Back\ResourceServiceContract' => 'InetStudio\TagsPackage\Tags\Services\Back\ResourceService',
        'InetStudio\TagsPackage\Tags\Contracts\Services\Back\UtilityServiceContract' => 'InetStudio\TagsPackage\Tags\Services\Back\UtilityService',
        'InetStudio\TagsPackage\Tags\Contracts\Services\ItemsServiceContract' => 'InetStudio\TagsPackage\Tags\Services\ItemsService',

        'InetStudio\TagsPackage\Tags\Contracts\Transformers\Front\Sitemap\ItemTransformerContract' => 'InetStudio\TagsPackage\Tags\Transformers\Front\Sitemap\ItemTransformer',
    ];

    public function provides()
    {
        return array_keys($this->bindings);
    }
}
