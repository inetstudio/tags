<?php

namespace InetStudio\Tags\Transformers\Back;

use League\Fractal\TransformerAbstract;
use InetStudio\Tags\Contracts\Models\TagModelContract;
use League\Fractal\Resource\Collection as FractalCollection;
use InetStudio\Tags\Contracts\Transformers\Back\SuggestionTransformerContract;

/**
 * Class SuggestionTransformer.
 */
class SuggestionTransformer extends TransformerAbstract implements SuggestionTransformerContract
{
    /**
     * @var string
     */
    private $type;

    /**
     * SuggestionTransformer constructor.
     *
     * @param $type
     */
    public function __construct($type)
    {
        $this->type = $type;
    }

    /**
     * Подготовка данных для отображения в выпадающих списках.
     *
     * @param TagModelContract $item
     *
     * @return array
     *
     * @throws \Throwable
     */
    public function transform(TagModelContract $item): array
    {
        if ($this->type && $this->type == 'autocomplete') {
            $modelClass = get_class($item);

            return [
                'value' => $item->name,
                'data' => [
                    'id' => $item->id,
                    'type' => $modelClass,
                    'title' => $item->name,
                    'path' => parse_url($item->href, PHP_URL_PATH),
                    'href' => $item->href,
                ],
            ];
        } else {
            return [
                'id' => $item->id,
                'name' => $item->name,
            ];
        }
    }

    /**
     * Обработка коллекции объектов.
     *
     * @param $items
     *
     * @return FractalCollection
     */
    public function transformCollection($items): FractalCollection
    {
        return new FractalCollection($items, $this);
    }
}
