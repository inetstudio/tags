<?php

namespace InetStudio\Tags\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Модель "ссылки" тег-материал.
 * 
 * Class Tagable
 *
 * @property int $tag_model_id
 * @property int $taggable_id
 * @property string $taggable_type
 * @property-read \InetStudio\Tags\Models\TagModel $tag
 * @method static \Illuminate\Database\Eloquent\Builder|\InetStudio\Tags\Models\TaggableModel whereTagModelId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\InetStudio\Tags\Models\TaggableModel whereTaggableId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\InetStudio\Tags\Models\TaggableModel whereTaggableType($value)
 * @mixin \Eloquent
 */
class TaggableModel extends Model
{
    /**
     * Связанная с моделью таблица.
     *
     * @var string
     */
    protected $table = 'taggables';

    /**
     * Атрибуты, для которых разрешено массовое назначение.
     *
     * @var array
     */
    protected $fillable = [
        'tag_model_id', 'taggable_id', 'taggable_type',
    ];

    /**
     * Обратное отношение "один ко многим" с моделью тега.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function tag()
    {
        return $this->belongsTo(TagModel::class, 'tag_model_id');
    }
}
