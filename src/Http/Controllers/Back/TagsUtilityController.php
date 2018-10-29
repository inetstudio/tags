<?php

namespace InetStudio\Tags\Http\Controllers\Back;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Cviebrock\EloquentSluggable\Services\SlugService;
use InetStudio\Tags\Contracts\Http\Responses\Back\Utility\SlugResponseContract;
use InetStudio\Tags\Contracts\Http\Controllers\Back\TagsUtilityControllerContract;
use InetStudio\Tags\Contracts\Http\Responses\Back\Utility\SuggestionsResponseContract;

/**
 * Class TagsUtilityController.
 */
class TagsUtilityController extends Controller implements TagsUtilityControllerContract
{
    /**
     * Используемые сервисы.
     *
     * @var array
     */
    private $services;

    /**
     * TagsUtilityController constructor.
     */
    public function __construct()
    {
        $this->services['tags'] = app()->make('InetStudio\Tags\Contracts\Services\Back\TagsServiceContract');
    }

    /**
     * Получаем slug для модели по строке.
     *
     * @param Request $request
     *
     * @return SlugResponseContract
     */
    public function getSlug(Request $request): SlugResponseContract
    {
        $id = (int) $request->get('id');
        $name = $request->get('name');

        $model = $this->services['tags']->getTagObject($id);

        $slug = ($name) ? SlugService::createSlug($model, 'slug', $name) : '';

        return app()->makeWith('InetStudio\Tags\Contracts\Http\Responses\Back\Utility\SlugResponseContract', [
            'slug' => $slug,
        ]);
    }

    /**
     * Возвращаем объекты для поля.
     *
     * @param Request $request
     *
     * @return SuggestionsResponseContract
     */
    public function getSuggestions(Request $request): SuggestionsResponseContract
    {
        $search = $request->get('q');
        $type = $request->get('type');

        $data = app()->make('InetStudio\Tags\Contracts\Services\Back\TagsServiceContract')
            ->getSuggestions($search, $type);

        return app()->makeWith('InetStudio\Tags\Contracts\Http\Responses\Back\Utility\SuggestionsResponseContract', [
            'suggestions' => $data,
        ]);
    }
}
