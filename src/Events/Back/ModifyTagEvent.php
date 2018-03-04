<?php

namespace InetStudio\Tags\Events\Back;

use Illuminate\Queue\SerializesModels;
use InetStudio\Tags\Contracts\Events\Back\ModifyTagEventContract;

/**
 * Class ModifyTagEvent.
 */
class ModifyTagEvent implements ModifyTagEventContract
{
    use SerializesModels;

    /**
     * @var
     */
    public $object;

    /**
     * ModifyTagEvent constructor.
     *
     * @param $object
     */
    public function __construct($object)
    {
        $this->object = $object;
    }
}
