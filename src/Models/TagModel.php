<?php

namespace InetStudio\Tags\Models;

use Spatie\Tags\Tag;
use Cocur\Slugify\Slugify;
use Phoenix\EloquentMeta\MetaTrait;
use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Venturecraft\Revisionable\RevisionableTrait;
use Spatie\MediaLibrary\HasMedia\Interfaces\HasMedia;

/**
 * Модель тега.
 */
class TagModel extends Tag implements HasMedia
{
    use MetaTrait;
    use Sluggable;
    use SoftDeletes;
    use HasMediaTrait;
    use RevisionableTrait;

    /**
     * Связанная с моделью таблица.
     *
     * @var string
     */
    protected $table = 'tags';

    /**
     * Атрибуты, для которых разрешено массовое назначение.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'slug', 'title', 'content',
        'type', 'order_column',
    ];

    /**
     * Атрибуты, которые должны быть преобразованы в даты.
     *
     * @var array
     */
    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public $translatable = [];

    /**
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */
    public function sluggable()
    {
        return [
            'slug' => [
                'source' => 'name',
                'unique' => true,
            ],
        ];
    }

    protected $revisionCreationsEnabled = true;

    /**
     * Обратное отношение "один ко многим" с моделью пользователя.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function author()
    {
        return $this->belongsTo(\App\User::class, 'author_id');
    }

    /**
     * Обратное отношение "один ко многим" с моделью пользователя.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function editor()
    {
        return $this->belongsTo(\App\User::class, 'last_editor_id');
    }

    /**
     * Отключаем генерацию slug в родительском классе Spatie\Tags\Tag.
     *
     * @return bool
     */
    public static function bootHasSlug()
    {
        return true;
    }

    /**
     * Переопределяем сохранение атрибута родительского класса, не используя перевод.
     *
     * @param string $key
     * @param mixed $value
     * @return $this
     */
    public function setAttribute($key, $value)
    {
        return Model::setAttribute($key, $value);
    }

    /**
     * Правила для транслита.
     *
     * @param Slugify $engine
     * @param $attribute
     * @return Slugify
     */
    public function customizeSlugEngine(Slugify $engine, $attribute)
    {
        $engine->addRule('а', 'a');
        $engine->addRule('б', 'b');
        $engine->addRule('в', 'v');
        $engine->addRule('г', 'g');
        $engine->addRule('д', 'd');
        $engine->addRule('е', 'e');
        $engine->addRule('ё', 'jo');
        $engine->addRule('ж', 'zh');
        $engine->addRule('з', 'z');
        $engine->addRule('и', 'i');
        $engine->addRule('й', 'j');
        $engine->addRule('к', 'k');
        $engine->addRule('л', 'l');
        $engine->addRule('м', 'm');
        $engine->addRule('н', 'n');
        $engine->addRule('о', 'o');
        $engine->addRule('п', 'p');
        $engine->addRule('р', 'r');
        $engine->addRule('с', 's');
        $engine->addRule('т', 't');
        $engine->addRule('у', 'u');
        $engine->addRule('ф', 'f');
        $engine->addRule('х', 'h');
        $engine->addRule('ц', 'c');
        $engine->addRule('ч', 'ch');
        $engine->addRule('ш', 'sh');
        $engine->addRule('щ', 'shh');
        $engine->addRule('ъ', '');
        $engine->addRule('ы', 'y');
        $engine->addRule('ь', '');
        $engine->addRule('э', 'je');
        $engine->addRule('ю', 'ju');
        $engine->addRule('я', 'ja');

        return $engine;
    }
}
