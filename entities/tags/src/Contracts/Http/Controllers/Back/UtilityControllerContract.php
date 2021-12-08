<?php

namespace InetStudio\TagsPackage\Tags\Contracts\Http\Controllers\Back;

use Illuminate\Http\Request;
use InetStudio\TagsPackage\Tags\Contracts\Services\Back\ItemsServiceContract;
use InetStudio\TagsPackage\Tags\Contracts\Services\Back\UtilityServiceContract;
use InetStudio\TagsPackage\Tags\Contracts\Http\Responses\Back\Utility\SlugResponseContract;
use InetStudio\TagsPackage\Tags\Contracts\Http\Responses\Back\Utility\SuggestionsResponseContract;

interface UtilityControllerContract
{
    public function getSlug(ItemsServiceContract $itemsService, Request $request): SlugResponseContract;

    public function getSuggestions(UtilityServiceContract $utilityService, Request $request): SuggestionsResponseContract;
}
