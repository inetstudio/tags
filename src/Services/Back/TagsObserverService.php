<?php

namespace InetStudio\Tags\Services\Back;

use InetStudio\Tags\Contracts\Models\TagModelContract;
use InetStudio\Tags\Contracts\Repositories\TagsRepositoryContract;
use InetStudio\Tags\Contracts\Services\Back\TagsObserverServiceContract;

/**
 * Class TagsObserverService.
 */
class TagsObserverService implements TagsObserverServiceContract
{
    /**
     * @var TagsRepositoryContract
     */
    private $repository;

    /**
     * TagsService constructor.
     *
     * @param TagsRepositoryContract $repository
     */
    public function __construct(TagsRepositoryContract $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Событие "объект создается".
     *
     * @param TagModelContract $item
     */
    public function creating(TagModelContract $item): void
    {
    }

    /**
     * Событие "объект создан".
     *
     * @param TagModelContract $item
     */
    public function created(TagModelContract $item): void
    {
    }

    /**
     * Событие "объект обновляется".
     *
     * @param TagModelContract $item
     */
    public function updating(TagModelContract $item): void
    {
    }

    /**
     * Событие "объект обновлен".
     *
     * @param TagModelContract $item
     */
    public function updated(TagModelContract $item): void
    {
    }

    /**
     * Событие "объект подписки удаляется".
     *
     * @param TagModelContract $item
     */
    public function deleting(TagModelContract $item): void
    {
    }

    /**
     * Событие "объект удален".
     *
     * @param TagModelContract $item
     */
    public function deleted(TagModelContract $item): void
    {
    }
}
