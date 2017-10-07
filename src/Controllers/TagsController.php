<?php

namespace InetStudio\Tags\Controllers;

use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Http\Controllers\Controller;
use InetStudio\Tags\Models\TagModel;
use Illuminate\Support\Facades\Session;
use InetStudio\Tags\Requests\SaveTagRequest;
use InetStudio\Tags\Transformers\TagTransformer;
use InetStudio\AdminPanel\Traits\DatatablesTrait;
use Cviebrock\EloquentSluggable\Services\SlugService;
use InetStudio\AdminPanel\Traits\MetaManipulationsTrait;
use InetStudio\AdminPanel\Traits\ImagesManipulationsTrait;

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
     * @param DataTables $dataTable
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(DataTables $dataTable)
    {
        $table = $this->generateTable($dataTable, 'tags', 'index');

        return view('admin.module.tags::pages.index', compact('table'));
    }

    /**
     * Datatables serverside.
     *
     * @return mixed
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
    public function create()
    {
        return view('admin.module.tags::pages.form', [
            'item' => new TagModel(),
        ]);
    }

    /**
     * Создание тега.
     *
     * @param SaveTagRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(SaveTagRequest $request)
    {
        return $this->save($request);
    }

    /**
     * Редактирование тега.
     *
     * @param null $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($id = null)
    {
        if (! is_null($id) && $id > 0 && $item = TagModel::find($id)) {
            return view('admin.module.tags::pages.form', [
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
    public function update(SaveTagRequest $request, $id = null)
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
    private function save($request, $id = null)
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

        \Event::fire('inetstudio.tags.cache.clear', $item->slug);

        Session::flash('success', 'Тег «'.$item->name.'» успешно '.$action);

        return redirect()->to(route('back.tags.edit', $item->fresh()->id));
    }

    /**
     * Удаление тега.
     *
     * @param null $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id = null)
    {
        if (! is_null($id) && $id > 0 && $item = TagModel::find($id)) {
            $slug = $item->slug;

            $item->delete();

            \Event::fire('inetstudio.tags.cache.clear', $slug);

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
    public function getSlug(Request $request)
    {
        $name = $request->get('name');
        $slug = SlugService::createSlug(TagModel::class, 'slug', $name);

        return response()->json($slug);
    }

    /**
     * Возвращаем теги для поля.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getSuggestions(Request $request)
    {
        $search = $request->get('q');
        $data = [];

        $data['items'] = TagModel::select(['id', 'name'])->where('name', 'LIKE', '%'.$search.'%')->get()->toArray();

        return response()->json($data);
    }
}
