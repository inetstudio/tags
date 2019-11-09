<?php

namespace InetStudio\TagsPackage\Tags\Models;

use Illuminate\Support\Arr;
use Laravel\Scout\Searchable;
use OwenIt\Auditing\Auditable;
use Illuminate\Support\Facades\Config;
use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\SoftDeletes;
use InetStudio\Uploads\Models\Traits\HasImages;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use InetStudio\MetaPackage\Meta\Models\Traits\HasMeta;
use InetStudio\TagsPackage\Tags\Models\Traits\HasTags;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use InetStudio\AdminPanel\Base\Models\Traits\SluggableTrait;
use Illuminate\Contracts\Container\BindingResolutionException;
use InetStudio\TagsPackage\Tags\Contracts\Models\TagModelContract;
use InetStudio\AdminPanel\Base\Models\Traits\Scopes\BuildQueryScopeTrait;
use InetStudio\SimpleCounters\Counters\Models\Traits\HasSimpleCountersTrait;

/**
 * Class TagModel.
 */
class TagModel extends Model implements TagModelContract
{
    use HasTags;
    use HasMeta;
    use Auditable;
    use Sluggable;
    use HasImages;
    use Searchable;
    use SoftDeletes;
    use SluggableTrait;
    use BuildQueryScopeTrait;
    use HasSimpleCountersTrait;

    /**
     * Тип сущности.
     */
    const ENTITY_TYPE = 'tag';

    /**
     * Часть слага для сущности.
     */
    const HREF = '/tag/';

    /**
     * Should the timestamps be audited?
     *
     * @var bool
     */
    protected $auditTimestamps = true;

    /**
     * Настройки для генерации изображений.
     *
     * @var array
     */
    protected $images = [
        'config' => 'tags',
        'model' => 'tag',
    ];

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
        'name',
        'slug',
        'title',
        'content',
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

    /**
     * Настройка полей для поиска.
     *
     * @return array
     */
    public function toSearchableArray()
    {
        $arr = Arr::only($this->toArray(), ['id', 'name', 'title', 'content']);

        return $arr;
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
     * Загрузка модели.
     */
    protected static function boot()
    {
        parent::boot();

        self::$buildQueryScopeDefaults['columns'] = [
            'id',
            'name',
            'slug',
            'title',
        ];

        self::$buildQueryScopeDefaults['relations'] = [
            'meta' => function (MorphMany $metaQuery) {
                $metaQuery->select(['metable_id', 'metable_type', 'key', 'value']);
            },

            'media' => function (MorphMany $mediaQuery) {
                $mediaQuery->select(
                    [
                        'id',
                        'model_id',
                        'model_type',
                        'collection_name',
                        'file_name',
                        'disk',
                        'mime_type',
                        'custom_properties',
                        'responsive_images',
                    ]
                );
            },

            'tags' => function (MorphToMany $tagsQuery) {
                $tagsQuery->select(['id', 'name', 'slug']);
            },

            'taggables' => function (HasMany $taggablesQuery) {
                $taggablesQuery->select(['tag_model_id', 'taggable_id', 'taggable_type']);
            },
        ];
    }

    /**
     * Сеттер атрибута name.
     *
     * @param $value
     */
    public function setNameAttribute($value): void
    {
        $this->attributes['name'] = trim(strip_tags($value));
    }

    /**
     * Сеттер атрибута title.
     *
     * @param $value
     */
    public function setTitleAttribute($value): void
    {
        $this->attributes['title'] = trim(strip_tags($value));
    }

    /**
     * Сеттер атрибута slug.
     *
     * @param $value
     */
    public function setSlugAttribute($value): void
    {
        $this->attributes['slug'] = trim(strip_tags($value));
    }

    /**
     * Сеттер атрибута content.
     *
     * @param $value
     */
    public function setContentAttribute($value): void
    {
        $value = (isset($value['text'])) ? $value['text'] : (! is_array($value) ? $value : '');

        $this->attributes['content'] = trim(str_replace('&nbsp;', ' ', $value));
    }

    /**
     * Геттер атрибута type.
     *
     * @return string
     */
    public function getTypeAttribute(): string
    {
        return self::ENTITY_TYPE;
    }

    /**
     * Геттер атрибута href.
     *
     * @return string
     */
    public function getHrefAttribute(): string
    {
        return url(self::HREF.($this->getAttribute('slug') ?: $this->getAttribute('id')));
    }

    /**
     * Отношение "один ко многим" с моделью "ссылок" на материалы.
     *
     * @return HasMany
     *
     * @throws BindingResolutionException
     */
    public function taggables(): HasMany
    {
        $taggableModel = app()->make('InetStudio\TagsPackage\Tags\Contracts\Models\TaggableModelContract');

        return $this->hasMany(
            get_class($taggableModel),
            'tag_model_id'
        );
    }

    /**
     * Handle dynamic method calls into the model.
     *
     * @param  string  $method
     * @param  array  $parameters
     *
     * @return mixed
     *
     * @throws BindingResolutionException
     */
    public function __call($method, $parameters)
    {
        $config = implode('.', ['tags.relationships', $method]);

        if (Config::has($config)) {
            $data = Config::get($config);

            $model = isset($data['model']) ? [app()->make($data['model'])] : [];
            $params = $data['params'] ?? [];

            return call_user_func_array([$this, $data['relationship']], array_merge($model, $params));
        }

        return parent::__call($method, $parameters);
    }

    /**
     * Get an attribute from the model.
     *
     * @param string $key
     *
     * @return mixed
     */
    public function getAttribute($key)
    {
        $config = implode('.', ['tags.relationships', $key]);

        if (Config::has($config)) {
            return $this->getRelationValue($key);
        }

        return parent::getAttribute($key);
    }

    /**
     * Get a relationship.
     *
     * @param string $key
     *
     * @return mixed
     */
    public function getRelationValue($key)
    {
        if ($this->relationLoaded($key)) {
            return $this->relations[$key];
        }

        $config = implode('.', ['tags.relationships', $key]);

        if (Config::has($config)) {
            return $this->getRelationshipFromMethod($key);
        }

        return parent::getRelationValue($key);
    }
}
