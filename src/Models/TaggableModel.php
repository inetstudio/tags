<?php

namespace InetStudio\Tags\Models;

use Illuminate\Database\Eloquent\Model;
use InetStudio\Tags\Contracts\Models\TaggableModelContract;

class TaggableModel extends Model implements TaggableModelContract
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
     * Атрибуты, которые должны быть преобразованы в даты.
     *
     * @var array
     */
    protected $dates = [
        'created_at',
        'updated_at',
    ];

    /**
     * Обратное отношение "один ко многим" с моделью тега.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function tag()
    {
        return $this->belongsTo(app()->make('InetStudio\Tags\Contracts\Models\TagModelContract'), 'tag_model_id');
    }
}
