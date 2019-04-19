<?php

namespace InetStudio\TagsPackage\Tags\Http\Responses\Back\Resource;

use Illuminate\Http\Request;
use InetStudio\TagsPackage\Tags\Contracts\Models\TagModelContract;
use InetStudio\TagsPackage\Tags\Contracts\Http\Responses\Back\Resource\SaveResponseContract;

/**
 * Class SaveResponse.
 */
class SaveResponse implements SaveResponseContract
{
    /**
     * @var TagModelContract
     */
    protected $item;

    /**
     * SaveResponse constructor.
     *
     * @param  TagModelContract  $item
     */
    public function __construct(TagModelContract $item)
    {
        $this->item = $item;
    }

    /**
     * Возвращаем ответ при сохранении объекта.
     * 
     * @param  Request  $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response
     */
    public function toResponse($request)
    {
        $item = $this->item->fresh();
        
        return response()->redirectToRoute(
            'back.tags.edit', 
            [
                $item['id'],
            ]
        );
    }
}
