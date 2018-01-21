<?php

namespace InetStudio\Tags\Http\Controllers\Back;

use Illuminate\View\View;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use InetStudio\Tags\Models\TagModel;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Session;
use InetStudio\Tags\Events\ModifyTagEvent;
use InetStudio\Tags\Transformers\Back\TagTransformer;
use Cviebrock\EloquentSluggable\Services\SlugService;
use InetStudio\Tags\Http\Requests\Back\SaveTagRequest;
use InetStudio\AdminPanel\Http\Controllers\Back\Traits\DatatablesTrait;
use InetStudio\Meta\Http\Controllers\Back\Traits\MetaManipulationsTrait;
use InetStudio\AdminPanel\Http\Controllers\Back\Traits\ImagesManipulationsTrait;

/**
 * Контроллер для управления тегами.
 *
 * Class ContestByTagStatusesController
 */
class TagsController extends Controller
{
    use DatatablesTrait;
    use MetaManipulationsTrait;
    use ImagesManipulationsTrait;

    /**
     * Список тегов.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Exception
     */
    public function index(): View
    {
        $table = $this->generateTable('tags', 'index');

        return view('admin.module.tags::back.pages.index', compact('table'));
    }

    /**
     * Datatables serverside.
     *
     * @return mixed
     * @throws \Exception
     */
    public function data()
    {
        $items = TagModel::withCount('taggables as taggables_count');

        return DataTables::of($items)
            ->setTransformer(new TagTransformer)
            ->rawColumns(['actions'])
            ->make();
    }

    /**
     * Добавление тега.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create(): View
    {
        return view('admin.module.tags::back.pages.form', [
            'item' => new TagModel(),
        ]);
    }

    /**
     * Создание тега.
     *
     * @param SaveTagRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(SaveTagRequest $request): RedirectResponse
    {
        return $this->save($request);
    }

    /**
     * Редактирование тега.
     *
     * @param null $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($id = null): View
    {
        if (! is_null($id) && $id > 0 && $item = TagModel::find($id)) {
            return view('admin.module.tags::back.pages.form', [
                'item' => $item,
            ]);
        } else {
            abort(404);
        }
    }

    /**
     * Обновление тега.
     *
     * @param SaveTagRequest $request
     * @param null $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(SaveTagRequest $request, $id = null): RedirectResponse
    {
        return $this->save($request, $id);
    }

    /**
     * Сохранение тега.
     *
     * @param SaveTagRequest $request
     * @param null $id
     * @return \Illuminate\Http\RedirectResponse
     */
    private function save($request, $id = null): RedirectResponse
    {
        if (! is_null($id) && $id > 0 && $item = TagModel::find($id)) {
            $action = 'отредактирован';
        } else {
            $action = 'создан';
            $item = new TagModel();
        }

        $item->name = strip_tags($request->get('name'));
        $item->title = strip_tags($request->get('title'));
        $item->content = $request->input('content.text');
        $item->save();

        $this->saveMeta($item, $request);
        $this->saveImages($item, $request, ['og_image', 'content'], 'tags');

        event(new ModifyTagEvent($item));

        Session::flash('success', 'Тег «'.$item->name.'» успешно '.$action);

        return response()->redirectToRoute('back.tags.edit', [
            $item->fresh()->id,
        ]);
    }

    /**
     * Удаление тега.
     *
     * @param null $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id = null): JsonResponse
    {
        if (! is_null($id) && $id > 0 && $item = TagModel::find($id)) {
            event(new ModifyTagEvent($item));

            $item->delete();

            return response()->json([
                'success' => true,
            ]);
        } else {
            return response()->json([
                'success' => false,
            ]);
        }
    }

    /**
     * Получаем slug для модели по строке.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getSlug(Request $request): JsonResponse
    {
        $name = $request->get('name');
        $slug = ($name) ? SlugService::createSlug(TagModel::class, 'slug', $name) : '';

        return response()->json($slug);
    }

    /**
     * Возвращаем теги для поля.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getSuggestions(Request $request): JsonResponse
    {
        $search = $request->get('q');
        $data = [];

        $data['items'] = TagModel::select(['id', 'name'])->where('name', 'LIKE', '%'.$search.'%')->get()->toArray();

        return response()->json($data);
    }
}
