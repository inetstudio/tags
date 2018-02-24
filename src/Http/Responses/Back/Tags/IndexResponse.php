<?php

namespace InetStudio\Tags\Http\Responses\Back\Tags;

use Illuminate\View\View;
use Illuminate\Contracts\Support\Responsable;
use InetStudio\Tags\Contracts\Http\Responses\Back\Tags\IndexResponseContract;

/**
 * Class IndexResponse.
 */
class IndexResponse implements IndexResponseContract, Responsable
{
    /**
     * @var array
     */
    private $data;

    /**
     * IndexResponse constructor.
     *
     * @param array $data
     */
    public function __construct(array $data)
    {
        $this->data = $data;
    }

    /**
     * Возвращаем ответ при открытии списка объектов.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return View
     */
    public function toResponse($request): View
    {
        return view('admin.module.tags::back.pages.index', $this->data);
    }
}
