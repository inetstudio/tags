<?php

namespace InetStudio\Tags\Observers;

use InetStudio\Tags\Contracts\Models\TagModelContract;
use InetStudio\Tags\Contracts\Observers\TagObserverContract;

/**
 * Class TagObserver.
 */
class TagObserver implements TagObserverContract
{
    /**
     * Используемые сервисы.
     *
     * @var array
     */
    protected $services;

    /**
     * TagObserver constructor.
     */
    public function __construct()
    {
        $this->services['tagsObserver'] = app()->make('InetStudio\Tags\Contracts\Services\Back\TagsObserverServiceContract');
    }

    /**
     * Событие "объект создается".
     *
     * @param TagModelContract $item
     */
    public function creating(TagModelContract $item): void
    {
        $this->services['tagsObserver']->creating($item);
    }

    /**
     * Событие "объект создан".
     *
     * @param TagModelContract $item
     */
    public function created(TagModelContract $item): void
    {
        $this->services['tagsObserver']->created($item);
    }

    /**
     * Событие "объект обновляется".
     *
     * @param TagModelContract $item
     */
    public function updating(TagModelContract $item): void
    {
        $this->services['tagsObserver']->updating($item);
    }

    /**
     * Событие "объект обновлен".
     *
     * @param TagModelContract $item
     */
    public function updated(TagModelContract $item): void
    {
        $this->services['tagsObserver']->updated($item);
    }

    /**
     * Событие "объект удаляется".
     *
     * @param TagModelContract $item
     */
    public function deleting(TagModelContract $item): void
    {
        $this->services['tagsObserver']->deleting($item);
    }

    /**
     * Событие "объект удален".
     *
     * @param TagModelContract $item
     */
    public function deleted(TagModelContract $item): void
    {
        $this->services['tagsObserver']->deleted($item);
    }
}
