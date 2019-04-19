<?php

namespace InetStudio\TagsPackage\Tags\Events\Back;

use Illuminate\Queue\SerializesModels;
use InetStudio\TagsPackage\Tags\Contracts\Models\TagModelContract;
use InetStudio\TagsPackage\Tags\Contracts\Events\Back\ModifyItemEventContract;

/**
 * Class ModifyItemEvent.
 */
class ModifyItemEvent implements ModifyItemEventContract
{
    use SerializesModels;

    /**
     * @var TagModelContract
     */
    public $item;

    /**
     * ModifyItemEvent constructor.
     *
     * @param TagModelContract $item
     */
    public function __construct(TagModelContract $item)
    {
        $this->item = $item;
    }
}
