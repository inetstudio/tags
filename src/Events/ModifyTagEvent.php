<?php

namespace InetStudio\Tags\Events;

use Illuminate\Queue\SerializesModels;

/**
 * Class ModifyTagEvent
 * @package InetStudio\Tags\Events
 */
class ModifyTagEvent
{
    use SerializesModels;

    /**
     * Объект тега.
     *
     * @var
     */
    public $object;

    /**
     * Create a new event instance.
     *
     * ModifyTagEvent constructor.
     * @param $object
     */
    public function __construct($object)
    {
        $this->object = $object;
    }
}
