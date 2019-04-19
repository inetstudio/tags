<?php

namespace InetStudio\TagsPackage\Tags\Models\Traits;

use ArrayAccess;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Contracts\Container\BindingResolutionException;
use InetStudio\TagsPackage\Tags\Contracts\Models\TagModelContract;

/**
 * Trait HasTags.
 */
trait HasTags
{
    use HasTagsCollection;

    /**
     * The Queued Tags.
     *
     * @var array
     */
    protected $queuedTags = [];

    /**
     * Get Tag class name.
     *
     * @return string
     *
     * @throws BindingResolutionException
     */
    public function getTagClassName(): string
    {
        $model = app()->make(TagModelContract::class);

        return get_class($model);
    }

    /**
     * Get all attached tags to the model.
     *
     * @return MorphToMany
     *
     * @throws BindingResolutionException
     */
    public function tags(): MorphToMany
    {
        $className = $this->getTagClassName();

        return $this->morphToMany($className, 'taggable')->withTimestamps();
    }

    /**
     * Attach the given tag(s) to the model.
     *
     * @param  int|string|array|ArrayAccess|TagModelContract  $tags
     *
     * @throws BindingResolutionException
     */
    public function setTagsAttribute($tags): void
    {
        if (! $this->exists) {
            $this->queuedTags = $tags;

            return;
        }

        $this->attachTags($tags);
    }

    /**
     * Boot the taggable trait for a model.
     */
    public static function bootHasTags()
    {
        static::created(
            function (Model $taggableModel) {
                if ($taggableModel->queuedTags) {
                    $taggableModel->attachTags($taggableModel->queuedTags);
                    $taggableModel->queuedTags = [];
                }
            }
        );

        static::deleted(
            function (Model $taggableModel) {
                $taggableModel->syncTags(null);
            }
        );
    }

    /**
     * Get the tag list.
     *
     * @param  string  $keyColumn
     *
     * @return array
     *
     * @throws BindingResolutionException
     */
    public function tagList(string $keyColumn = 'slug'): array
    {
        return $this->tags()->pluck('name', $keyColumn)->toArray();
    }

    /**
     * Scope query with all the given tags.
     *
     * @param  Builder  $query
     * @param  int|string|array|ArrayAccess|TagModelContract  $tags
     * @param  string  $column
     *
     * @return Builder
     *
     * @throws BindingResolutionException
     */
    public function scopeWithAllTags(Builder $query, $tags, string $column = 'slug'): Builder
    {
        $tags = $this->isTagsStringBased($tags)
            ? $tags : $this->hydrateTags($tags)->pluck($column);

        collect($tags)->each(
            function ($tag) use ($query, $column) {
                $query->whereHas(
                    'tags',
                    function (Builder $query) use ($tag, $column) {
                        return $query->where($column, $tag);
                    }
                );
            }
        );

        return $query;
    }

    /**
     * Scope query with any of the given tags.
     *
     * @param  Builder  $query
     * @param  int|string|array|ArrayAccess|TagModelContract  $tags
     * @param  string  $column
     *
     * @return Builder
     *
     * @throws BindingResolutionException
     */
    public function scopeWithAnyTags(Builder $query, $tags, string $column = 'slug'): Builder
    {
        $tags = $this->isTagsStringBased($tags)
            ? $tags : $this->hydrateTags($tags)->pluck($column);

        return $query->whereHas(
            'tags',
            function (Builder $query) use ($tags, $column) {
                $query->whereIn($column, (array) $tags);
            }
        );
    }

    /**
     * Scope query with any of the given tags.
     *
     * @param  Builder  $query
     * @param  int|string|array|ArrayAccess|TagModelContract  $tags
     * @param  string  $column
     *
     * @return Builder
     *
     * @throws BindingResolutionException
     */
    public function scopeWithTags(Builder $query, $tags, string $column = 'slug'): Builder
    {
        return $this->scopeWithAnyTags($query, $tags, $column);
    }

    /**
     * Scope query without the given tags.
     *
     * @param  Builder  $query
     * @param  int|string|array|ArrayAccess|TagModelContract  $tags
     * @param  string  $column
     *
     * @return Builder
     *
     * @throws BindingResolutionException
     */
    public function scopeWithoutTags(Builder $query, $tags, string $column = 'slug'): Builder
    {
        $tags = $this->isTagsStringBased($tags)
            ? $tags : $this->hydrateTags($tags)->pluck($column);

        return $query->whereDoesntHave(
            'tags',
            function (Builder $query) use ($tags, $column) {
                $query->whereIn($column, (array) $tags);
            }
        );
    }

    /**
     * Scope query without any tags.
     *
     * @param  Builder  $query
     *
     * @return Builder
     */
    public function scopeWithoutAnyTags(Builder $query): Builder
    {
        return $query->doesntHave('tags');
    }

    /**
     * Attach the given tag(s) to the model.
     *
     * @param  int|string|array|ArrayAccess|TagModelContract  $tags
     *
     * @return $this
     *
     * @throws BindingResolutionException
     */
    public function attachTags($tags): self
    {
        $this->setTags($tags, 'syncWithoutDetaching');

        return $this;
    }

    /**
     * Sync the given tag(s) to the model.
     *
     * @param  int|string|array|ArrayAccess|TagModelContract|null  $tags
     *
     * @return $this
     *
     * @throws BindingResolutionException
     */
    public function syncTags($tags): self
    {
        $this->setTags($tags, 'sync');

        return $this;
    }

    /**
     * Detach the given tag(s) from the model.
     *
     * @param  int|string|array|ArrayAccess|TagModelContract  $tags
     *
     * @return $this
     *
     * @throws BindingResolutionException
     */
    public function detachTags($tags): self
    {
        $this->setTags($tags, 'detach');

        return $this;
    }

    /**
     * Set the given tag(s) to the model.
     *
     * @param  int|string|array|ArrayAccess|TagModelContract  $tags
     * @param  string  $action
     *
     * @throws BindingResolutionException
     */
    protected function setTags($tags, string $action): void
    {
        // Fix exceptional event name
        $event = $action === 'syncWithoutDetaching' ? 'attach' : $action;

        // Hydrate Tags
        $tags = $this->hydrateTags($tags)->pluck('id')->toArray();

        // Fire the Tag syncing event
        static::$dispatcher->dispatch('inetstudio.tags.'.$event.'ing', [$this, $tags]);

        // Set Tags
        $this->tags()->$action($tags);

        // Fire the Tag synced event
        static::$dispatcher->dispatch('inetstudio.tags.'.$event.'ed', [$this, $tags]);
    }

    /**
     * Hydrate tags.
     *
     * @param  int|string|array|ArrayAccess|TagModelContract  $tags
     *
     * @return Collection
     *
     * @throws BindingResolutionException
     */
    protected function hydrateTags($tags): Collection
    {
        $isTagsStringBased = $this->isTagsStringBased($tags);
        $isTagsIntBased = $this->isTagsIntBased($tags);
        $field = $isTagsStringBased ? 'slug' : 'id';
        $className = $this->getTagClassName();

        return $isTagsStringBased || $isTagsIntBased
            ? $className::query()->whereIn($field, (array) $tags)->get() : collect($tags);
    }
}
