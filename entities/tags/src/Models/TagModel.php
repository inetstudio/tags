<?php

namespace InetStudio\TagsPackage\Tags\Models;

use Cocur\Slugify\Slugify;
use Illuminate\Support\Arr;
use Laravel\Scout\Searchable;
use OwenIt\Auditing\Auditable;
use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\SoftDeletes;
use InetStudio\UploadsPackage\Uploads\Models\Traits\HasMedia;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use InetStudio\MetaPackage\Meta\Models\Traits\HasMeta;
use InetStudio\TagsPackage\Tags\Models\Traits\HasTags;
use Cviebrock\EloquentSluggable\SluggableScopeHelpers;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use InetStudio\TagsPackage\Tags\Contracts\Models\TagModelContract;
use InetStudio\AdminPanel\Base\Models\Traits\Scopes\BuildQueryScopeTrait;
use InetStudio\SimpleCounters\Counters\Models\Traits\HasSimpleCountersTrait;

class TagModel extends Model implements TagModelContract
{
    use HasTags;
    use HasMeta;
    use Auditable;
    use Sluggable;
    use HasMedia;
    use Searchable;
    use SoftDeletes;
    use BuildQueryScopeTrait;
    use SluggableScopeHelpers;
    use HasSimpleCountersTrait;

    const ENTITY_TYPE = 'tag';

    const HREF = '/tag/';

    protected bool $auditTimestamps = true;

    protected $table = 'tags';

    protected $fillable = [
        'name',
        'slug',
        'title',
        'content',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function toSearchableArray()
    {
        $arr = Arr::only($this->toArray(), ['id', 'name', 'title', 'content']);

        return $arr;
    }

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'name',
                'unique' => true,
            ],
        ];
    }

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
                        'conversions_disk',
                        'uuid',
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

    public function getTypeAttribute(): string
    {
        return self::ENTITY_TYPE;
    }

    public function getHrefAttribute(): string
    {
        return url(self::HREF.($this->getAttribute('slug') ?: $this->getAttribute('id')));
    }

    public function related(): HasMany
    {
        $taggableModel = resolve('InetStudio\TagsPackage\Tags\Contracts\Models\TaggableModelContract');

        return $this->hasMany(
            get_class($taggableModel),
            'tag_model_id'
        );
    }

    public function customizeSlugEngine(Slugify $engine)
    {
        $rules = [
            'а' => 'a',
            'б' => 'b',
            'в' => 'v',
            'г' => 'g',
            'д' => 'd',
            'е' => 'e',
            'ё' => 'jo',
            'ж' => 'zh',
            'з' => 'z',
            'и' => 'i',
            'й' => 'j',
            'к' => 'k',
            'л' => 'l',
            'м' => 'm',
            'н' => 'n',
            'о' => 'o',
            'п' => 'p',
            'р' => 'r',
            'с' => 's',
            'т' => 't',
            'у' => 'u',
            'ф' => 'f',
            'х' => 'h',
            'ц' => 'c',
            'ч' => 'ch',
            'ш' => 'sh',
            'щ' => 'shh',
            'ъ' => '',
            'ы' => 'y',
            'ь' => '',
            'э' => 'je',
            'ю' => 'ju',
            'я' => 'ja',
        ];

        $engine->addRules($rules);

        return $engine;
    }

    public function getMediaConfig(): array
    {
        return config('tags.media', []);
    }
}
