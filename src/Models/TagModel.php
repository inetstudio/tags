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
use Cviebrock\EloquentSluggable\SluggableScopeHelpers;
use Spatie\MediaLibrary\HasMedia\Interfaces\HasMediaConversions;

/**
 * Модель тега.
 *
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property string $title
 * @property string|null $content
 * @property string|null $type
 * @property int|null $order_column
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property \Carbon\Carbon|null $deleted_at
 * @property-read \App\User $author
 * @property-read \App\User $editor
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\MediaLibrary\Media[] $media
 * @property-read \Illuminate\Database\Eloquent\Collection|\Phoenix\EloquentMeta\Meta[] $meta
 * @property-read \Illuminate\Database\Eloquent\Collection|\Venturecraft\Revisionable\Revision[] $revisionHistory
 * @method static \Illuminate\Database\Eloquent\Builder|\InetStudio\Tags\Models\TagModel findSimilarSlugs(\Illuminate\Database\Eloquent\Model $model, $attribute, $config, $slug)
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Query\Builder|\InetStudio\Tags\Models\TagModel onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|\Spatie\Tags\Tag ordered($direction = 'asc')
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|\InetStudio\Tags\Models\TagModel whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\InetStudio\Tags\Models\TagModel whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\InetStudio\Tags\Models\TagModel whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\InetStudio\Tags\Models\TagModel whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\InetStudio\Tags\Models\TagModel whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\InetStudio\Tags\Models\TagModel whereOrderColumn($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\InetStudio\Tags\Models\TagModel whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\InetStudio\Tags\Models\TagModel whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\InetStudio\Tags\Models\TagModel whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\InetStudio\Tags\Models\TagModel whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\InetStudio\Tags\Models\TagModel withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|\Spatie\Tags\Tag withType($type = null)
 * @method static \Illuminate\Database\Query\Builder|\InetStudio\Tags\Models\TagModel withoutTrashed()
 * @mixin \Eloquent
 */
class TagModel extends Tag implements HasMediaConversions
{
    use MetaTrait;
    use Sluggable;
    use SoftDeletes;
    use HasMediaTrait;
    use RevisionableTrait;
    use SluggableScopeHelpers;

    const HREF = '/tag/';

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
     * @return Slugify
     */
    public function customizeSlugEngine(Slugify $engine)
    {
        $rules = [
            'а' => 'a', 'б' => 'b', 'в' => 'v', 'г' => 'g', 'д' => 'd', 'е' => 'e', 'ё' => 'jo', 'ж' => 'zh',
            'з' => 'z', 'и' => 'i', 'й' => 'j', 'к' => 'k', 'л' => 'l', 'м' => 'm', 'н' => 'n', 'о' => 'o', 'п' => 'p',
            'р' => 'r', 'с' => 's', 'т' => 't', 'у' => 'u', 'ф' => 'f', 'х' => 'h', 'ц' => 'c', 'ч' => 'ch',
            'ш' => 'sh', 'щ' => 'shh', 'ъ' => '', 'ы' => 'y', 'ь' => '', 'э' => 'je', 'ю' => 'ju', 'я' => 'ja',
        ];

        $engine->addRules($rules);

        return $engine;
    }

    /**
     * Ссылка на тег.
     *
     * @return \Illuminate\Contracts\Routing\UrlGenerator|string
     */
    public function getHrefAttribute()
    {
        return url(self::HREF . (!empty($this->slug) ? $this->slug : $this->id));
    }

    public function registerMediaConversions()
    {
        $quality = (config('tags.images.quality')) ? config('tags.images.quality') : 75;

        if (config('tags.images.conversions')) {
            foreach (config('tags.images.conversions') as $collection => $image) {
                foreach ($image as $crop) {
                    foreach ($crop as $conversion) {
                        $this->addMediaConversion($conversion['name'])
                            ->quality($quality)
                            ->width($conversion['size']['width'])
                            ->height($conversion['size']['height'])
                            ->performOnCollections($collection);
                    }
                }
            }
        }
    }
}
