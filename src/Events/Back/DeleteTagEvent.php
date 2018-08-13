<?php

namespace InetStudio\Tags\Events\Back;

use Illuminate\Queue\SerializesModels;
use InetStudio\Tags\Contracts\Events\Back\DeleteTagEventContract;

/**
 * Class DeleteTagEvent.
 */
class DeleteTagEvent implements DeleteTagEventContract
{
    use SerializesModels;

    /**
     * @var string
     */
    public $className;

    /**
     * @var int
     */
    public $id;

    /**
     * DeleteTagEvent constructor.
     *
     * @param string $className
     * @param int $id
     */
    public function __construct(string $className, int $id)
    {
        $this->className = $className;
        $this->id = $id;
    }
}
