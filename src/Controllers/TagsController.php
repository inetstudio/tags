<?php

namespace InetStudio\Tags\Controllers;

use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use App\Http\Controllers\Controller;
use InetStudio\Tags\Models\TagModel;
use Illuminate\Support\Facades\Session;
use InetStudio\Tags\Requests\SaveTagRequest;
use InetStudio\Tags\Transformers\TagTransformer;
use Cviebrock\EloquentSluggable\Services\SlugService;

/**
 * Контроллер для управления тегами.
 *
 * Class ContestByTagStatusesController
 */
class TagsController extends Controller
{
    /**
     * Список тегов.
     *
     * @param Datatables $dataTable
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Datatables $dataTable)
    {
        $table = $dataTable->getHtmlBuilder();

        $table->columns([
            ['data' => 'name', 'name' => 'name', 'title' => 'Название'],
            ['data' => 'created_at', 'name' => 'created_at', 'title' => 'Дата создания'],
            ['data' => 'updated_at', 'name' => 'updated_at', 'title' => 'Дата обновления'],
            ['data' => 'actions', 'name' => 'actions', 'title' => 'Действия', 'orderable' => false, 'searchable' => false],
        ]);

        $table->ajax([
            'url' => route('back.tags.data'),
            'type' => 'POST',
            'data' => 'function(data) { data._token = $(\'meta[name="csrf-token"]\').attr(\'content\'); }',
        ]);

        $table->parameters([
            'paging' => true,
            'pagingType' => 'full_numbers',
            'searching' => true,
            'info' => false,
            'searchDelay' => 350,
            'language' => [
                'url' => asset('admin/js/plugins/datatables/locales/russian.json'),
            ],
        ]);

        return view('admin.module.tags::pages.tags.index', compact('table'));
    }

    /**
     * Datatables serverside.
     *
     * @return mixed
     */
    public function data()
    {
        $items = TagModel::query();

        return Datatables::of($items)
            ->setTransformer(new TagTransformer)
            ->escapeColumns(['actions'])
            ->make();
    }

    /**
     * Добавление тега.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        return view('admin.module.tags::pages.tags.form', [
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

            return view('admin.module.tags::pages.tags.form', [
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
        if (! is_null($id) && $id > 0) {
            $edit = true;
            $item = TagModel::find($id);

            if (empty($item)) {
                abort(404);
            }
        } else {
            $edit = false;
            $item = new TagModel();
        }

        $item->name = strip_tags($request->get('name'));
        $item->title = strip_tags($request->get('title'));
        $item->content = $request->get('content');
        $item->save();

        $this->saveMeta($item, $request);
        $this->saveImages($item, $request, ['og_image']);

        $action = ($edit) ? 'отредактирован' : 'создан';
        Session::flash('success', 'Тег «'.$item->name.'» успешно '.$action);

        return redirect()->to(route('back.tags.edit', $item->fresh()->id));
    }

    /**
     * Сохраняем мета теги.
     *
     * @param TagModel $item
     * @param SaveTagRequest $request
     */
    private function saveMeta($item, $request)
    {
        if ($request->has('meta')) {
            foreach ($request->get('meta') as $key => $value) {
                $item->updateMeta($key, $value);
            }
        }
    }

    /**
     * Сохраняем изображения.
     *
     * @param TagModel $item
     * @param SaveTagRequest $request
     * @param array $images
     */
    private function saveImages($item, $request, $images)
    {
        foreach ($images as $name) {
            $properties = $request->get($name);

            if (isset($properties['base64']) && isset($properties['filename'])) {
                $image = $properties['base64'];
                $filename = $properties['filename'];

                if (isset($properties['type']) && $properties['type'] == 'single') {
                    $item->clearMediaCollection($name);
                }

                array_forget($properties, ['type', 'base64', 'filename']);
                $properties = array_filter($properties);

                $item->addMediaFromBase64($image)
                    ->withCustomProperties($properties)
                    ->usingName(pathinfo($filename, PATHINFO_FILENAME))
                    ->usingFileName(md5($image).'.'.pathinfo($filename, PATHINFO_EXTENSION))
                    ->toMediaCollection($name, 'tags');
            } else {
                if (isset($properties['type']) && $properties['type'] == 'single') {
                    array_forget($properties, 'type');

                    $properties = array_filter($properties);

                    $media = $item->getFirstMedia($name);
                    $media->custom_properties = $properties;
                    $media->save();
                }
            }
        }
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
