<?php

namespace InetStudio\Tags\Models;

use Spatie\Tags\Tag;
use Cocur\Slugify\Slugify;
use Laravel\Scout\Searchable;
use Spatie\MediaLibrary\Media;
use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;
use InetStudio\Meta\Models\Traits\Metable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\Image\Exceptions\InvalidManipulation;
use Venturecraft\Revisionable\RevisionableTrait;
use Cviebrock\EloquentSluggable\SluggableScopeHelpers;
use InetStudio\Meta\Contracts\Models\Traits\MetableContract;
use Spatie\MediaLibrary\HasMedia\Interfaces\HasMediaConversions;
use InetStudio\SimpleCounters\Models\Traits\HasSimpleCountersTrait;


/**
 * InetStudio\Tags\Models\TagModel
 *
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property string $title
 * @property string|null $content
 * @property string|null $type
 * @property int|null $order_column
 * @property int $author_id
 * @property int $last_editor_id
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property \Carbon\Carbon|null $deleted_at
 * @property-read \App\User $author
 * @property-read \Illuminate\Database\Eloquent\Collection|\InetStudio\SimpleCounters\Models\SimpleCounterModel[] $counters
 * @property-read \App\User $editor
 * @property-read \Illuminate\Contracts\Routing\UrlGenerator|string $href
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\MediaLibrary\Media[] $media
 * @property-read \Illuminate\Database\Eloquent\Collection|\InetStudio\Meta\Models\MetaModel[] $meta
 * @property-read \Illuminate\Database\Eloquent\Collection|\Venturecraft\Revisionable\Revision[] $revisionHistory
 * @property-read \Illuminate\Database\Eloquent\Collection|\InetStudio\Tags\Models\TaggableModel[] $taggables
 * @method static \Illuminate\Database\Eloquent\Builder|\InetStudio\Tags\Models\TagModel findSimilarSlugs($attribute, $config, $slug)
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Query\Builder|\InetStudio\Tags\Models\TagModel onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|\Spatie\Tags\Tag ordered($direction = 'asc')
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|\InetStudio\Tags\Models\TagModel whereAuthorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\InetStudio\Tags\Models\TagModel whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\InetStudio\Tags\Models\TagModel whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\InetStudio\Tags\Models\TagModel whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\InetStudio\Tags\Models\TagModel whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\InetStudio\Tags\Models\TagModel whereLastEditorId($value)
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
class TagModel extends Tag implements MetableContract, HasMediaConversions
{
    use Metable;
    use Sluggable;
    use Searchable;
    use SoftDeletes;
    use HasMediaTrait;
    use RevisionableTrait;
    use SluggableScopeHelpers;
    use HasSimpleCountersTrait;

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
     * Отношение "один ко многим" с моделью "ссылок" на материалы.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function taggables()
    {
        return $this->hasMany(TaggableModel::class, 'tag_model_id');
    }

    /**
     * Настройка полей для поиска.
     *
     * @return array
     */
    public function toSearchableArray()
    {
        $arr = array_only($this->toArray(), ['id', 'name', 'title', 'content']);

        return $arr;
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
     * Возвращаем конфиг для генерации slug модели.
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

    /**
     * Переопределяем поиск тега по строке.
     *
     * @param string $name
     * @param string|null $type
     * @param string|null $locale
     * @return Model|null|static
     */
    public static function findFromString(string $name, string $type = null, string $locale = null)
    {
        return static::query()
            ->where('slug', $name)
            ->first();
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
        return url(self::HREF.(! empty($this->slug) ? $this->slug : $this->id));
    }

    /**
     * Регистрируем преобразования изображений.
     *
     * @param Media|null $media
     * @throws InvalidManipulation
     */
    public function registerMediaConversions(Media $media = null)
    {
        $quality = (config('tags.images.quality')) ? config('tags.images.quality') : 75;

        if (config('tags.images.conversions')) {
            foreach (config('tags.images.conversions') as $collection => $image) {
                foreach ($image as $crop) {
                    foreach ($crop as $conversion) {
                        $imageConversion = $this->addMediaConversion($conversion['name']);

                        if (isset($conversion['size']['width'])) {
                            $imageConversion->width($conversion['size']['width']);
                        }

                        if (isset($conversion['size']['height'])) {
                            $imageConversion->height($conversion['size']['height']);
                        }

                        if (isset($conversion['fit']['width']) && isset($conversion['fit']['height'])) {
                            $imageConversion->fit('max', $conversion['fit']['width'], $conversion['fit']['height']);
                        }

                        if (isset($conversion['quality'])) {
                            $imageConversion->quality($conversion['quality']);
                            $imageConversion->optimize();
                        } else {
                            $imageConversion->quality($quality);
                        }

                        $imageConversion->performOnCollections($collection);
                    }
                }
            }
        }
    }
}
