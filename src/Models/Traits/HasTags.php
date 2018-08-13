<?php

namespace InetStudio\Tags\Models\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use InetStudio\Tags\Contracts\Models\TagModelContract;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

/**
 * Trait HasTags.
 */
trait HasTags
{
    /**
     * The Queued tags.
     *
     * @var array
     */
    protected $queuedTags = [];

    /**
     * Get tag class name.
     *
     * @return string
     */
    public static function getTagClassName(): string
    {
        $model = app()->make('InetStudio\Tags\Contracts\Models\TagModelContract');

        return get_class($model);
    }

    /**
     * Get all attached tags to the model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
     */
    public function tags(): MorphToMany
    {
        return $this->morphToMany(static::getTagClassName(), 'taggable')->withTimestamps();
    }

    /**
     * Attach the given tag(s) to the model.
     *
     * @param int|string|array|\ArrayAccess|TagModelContract $tags
     *
     * @return void
     */
    public function setTagsAttribute($tags)
    {
        if (! $this->exists) {
            $this->queuedTags = $tags;

            return;
        }

        $this->categorize($tags);
    }

    /**
     * Boot the taggable trait for a model.
     *
     * @return void
     */
    public static function bootHasTags()
    {
        static::created(function (Model $taggableModel) {
            if ($taggableModel->queuedTags) {
                $taggableModel->attachTags($taggableModel->queuedTags);
                $taggableModel->queuedTags = [];
            }
        });

        static::deleted(function (Model $taggableModel) {
            $taggableModel->syncTags(null);
        });
    }

    /**
     * Получаем список тегов.
     *
     * @param string $keyColumn
     *
     * @return array
     */
    public function tagList(string $keyColumn = 'slug'): array
    {
        return $this->tags()->pluck('name', $keyColumn)->toArray();
    }

    /**
     * Scope query with all the given tags.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param int|string|array|\ArrayAccess|TagModelContract $tags
     * @param string $column
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWithAllTags(Builder $query, $tags, string $column = 'slug'): Builder
    {
        $tags = static::isTagsStringBased($tags)
            ? $tags : static::hydrateTags($tags)->pluck($column);

        collect($tags)->each(function ($tag) use ($query, $column) {
            $query->whereHas('tags', function (Builder $query) use ($tag, $column) {
                return $query->where($column, $tag);
            });
        });

        return $query;
    }

    /**
     * Scope query with any of the given tags.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param int|string|array|\ArrayAccess|TagModelContract $tags
     * @param string $column
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWithAnyTags(Builder $query, $tags, string $column = 'slug'): Builder
    {
        $tags = static::isTagsStringBased($tags)
            ? $tags : static::hydrateTags($tags)->pluck($column);

        return $query->whereHas('tags', function (Builder $query) use ($tags, $column) {
            $query->whereIn($column, (array) $tags);
        });
    }

    /**
     * Scope query with any of the given tags.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param int|string|array|\ArrayAccess|TagModelContract $tags
     * @param string $column
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWithTags(Builder $query, $tags, string $column = 'slug'): Builder
    {
        return static::scopeWithAnyTags($query, $tags, $column);
    }

    /**
     * Scope query without the given tags.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param int|string|array|\ArrayAccess|TagModelContract $tags
     * @param string $column
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWithoutTags(Builder $query, $tags, string $column = 'slug'): Builder
    {
        $tags = static::isTagsStringBased($tags)
            ? $tags : static::hydrateTags($tags)->pluck($column);

        return $query->whereDoesntHave('tags', function (Builder $query) use ($tags, $column) {
            $query->whereIn($column, (array) $tags);
        });
    }

    /**
     * Scope query without any tags.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWithoutAnyTags(Builder $query): Builder
    {
        return $query->doesntHave('tags');
    }

    /**
     * Attach the given tag(s) to the model.
     *
     * @param int|string|array|\ArrayAccess|TagModelContract $tags
     *
     * @return $this
     */
    public function attachTags($tags)
    {
        static::setTags($tags, 'syncWithoutDetaching');

        return $this;
    }

    /**
     * Синхронизируем теги модели.
     *
     * @param int|string|array|\ArrayAccess|TagModelContract|null $tags
     *
     * @return $this
     */
    public function syncTags($tags)
    {
        static::setTags($tags, 'sync');

        return $this;
    }

    /**
     * Detach the given tag(s) from the model.
     *
     * @param int|string|array|\ArrayAccess|TagModelContract $tags
     *
     * @return $this
     */
    public function detachTags($tags)
    {
        static::setTags($tags, 'detach');

        return $this;
    }

    /**
     * Determine if the model has any the given tags.
     *
     * @param int|string|array|\ArrayAccess|TagModelContract $tags
     *
     * @return bool
     */
    public function hasTag($tags): bool
    {
        // Single tag slug
        if (is_string($tags)) {
            return $this->tags->contains('slug', $tags);
        }

        // Single tag id
        if (is_int($tags)) {
            return $this->tags->contains('id', $tags);
        }

        // Single tag model
        if ($tags instanceof tagModelContract) {
            return $this->tags->contains('slug', $tags->slug);
        }

        // Array of tag slugs
        if (is_array($tags) && isset($tags[0]) && is_string($tags[0])) {
            return ! $this->tags->pluck('slug')->intersect($tags)->isEmpty();
        }

        // Array of tag ids
        if (is_array($tags) && isset($tags[0]) && is_int($tags[0])) {
            return ! $this->tags->pluck('id')->intersect($tags)->isEmpty();
        }

        // Collection of tag models
        if ($tags instanceof Collection) {
            return ! $tags->intersect($this->tags->pluck('slug'))->isEmpty();
        }

        return false;
    }

    /**
     * Determine if the model has any the given tags.
     *
     * @param int|string|array|\ArrayAccess|TagModelContract $tags
     *
     * @return bool
     */
    public function hasAnyTag($tags): bool
    {
        return static::hasTag($tags);
    }

    /**
     * Determine if the model has all of the given tags.
     *
     * @param int|string|array|\ArrayAccess|TagModelContract $tags
     *
     * @return bool
     */
    public function hasAllTags($tags): bool
    {
        // Single tag slug
        if (is_string($tags)) {
            return $this->tags->contains('slug', $tags);
        }

        // Single tag id
        if (is_int($tags)) {
            return $this->tags->contains('id', $tags);
        }

        // Single tag model
        if ($tags instanceof tagModelContract) {
            return $this->tags->contains('slug', $tags->slug);
        }

        // Array of tag slugs
        if (is_array($tags) && isset($tags[0]) && is_string($tags[0])) {
            return $this->tags->pluck('slug')->count() === count($tags)
                && $this->tags->pluck('slug')->diff($tags)->isEmpty();
        }

        // Array of tag ids
        if (is_array($tags) && isset($tags[0]) && is_int($tags[0])) {
            return $this->tags->pluck('id')->count() === count($tags)
                && $this->tags->pluck('id')->diff($tags)->isEmpty();
        }

        // Collection of tag models
        if ($tags instanceof Collection) {
            return $this->tags->count() === $tags->count() && $this->tags->diff($tags)->isEmpty();
        }

        return false;
    }

    /**
     * Set the given tag(s) to the model.
     *
     * @param int|string|array|\ArrayAccess|TagModelContract $tags
     * @param string $action
     *
     * @return void
     */
    protected function setTags($tags, string $action)
    {
        // Fix exceptional event name
        $event = $action === 'syncWithoutDetaching' ? 'attach' : $action;

        // Hydrate tags
        $tags = static::hydrateTags($tags)->pluck('id')->toArray();

        // Fire the tag syncing event
        static::$dispatcher->dispatch("inetstudio.tags.{$event}ing", [$this, $tags]);

        // Set tags
        $this->tags()->$action($tags);

        // Fire the tag synced event
        static::$dispatcher->dispatch("inetstudio.tags.{$event}ed", [$this, $tags]);
    }

    /**
     * Hydrate tags.
     *
     * @param int|string|array|\ArrayAccess|TagModelContract $tags
     *
     * @return \Illuminate\Support\Collection
     */
    protected function hydrateTags($tags)
    {
        $isTagsStringBased = static::isTagsStringBased($tags);
        $isTagsIntBased = static::isTagsIntBased($tags);
        $field = $isTagsStringBased ? 'slug' : 'id';
        $className = static::getTagClassName();

        return $isTagsStringBased || $isTagsIntBased
            ? $className::query()->whereIn($field, (array) $tags)->get() : collect($tags);
    }

    /**
     * Determine if the given tag(s) are string based.
     *
     * @param int|string|array|\ArrayAccess|TagModelContract $tags
     *
     * @return bool
     */
    protected function isTagsStringBased($tags)
    {
        return is_string($tags) || (is_array($tags) && isset($tags[0]) && is_string($tags[0]));
    }

    /**
     * Determine if the given tag(s) are integer based.
     *
     * @param int|string|array|\ArrayAccess|TagModelContract $tags
     *
     * @return bool
     */
    protected function isTagsIntBased($tags)
    {
        return is_int($tags) || (is_array($tags) && isset($tags[0]) && is_int($tags[0]));
    }
}
