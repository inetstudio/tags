<?php

namespace InetStudio\TagsPackage\Tags\Events\Back;

use Illuminate\Queue\SerializesModels;
use InetStudio\TagsPackage\Tags\Contracts\Models\TagModelContract;
use InetStudio\TagsPackage\Tags\Contracts\Events\Back\ModifyItemEventContract;

class ModifyItemEvent implements ModifyItemEventContract
{
    use SerializesModels;

    public function __construct(
        public TagModelContract $item
    ) {}
}
