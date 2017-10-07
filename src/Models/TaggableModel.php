<?php

namespace InetStudio\Tags\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Модель "ссылки" тег-материал.
 *
 * Class Tagable
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
