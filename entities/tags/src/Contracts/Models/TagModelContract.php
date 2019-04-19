<?php

namespace InetStudio\TagsPackage\Tags\Contracts\Models;

use OwenIt\Auditing\Contracts\Auditable;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use InetStudio\Meta\Contracts\Models\Traits\MetableContract;
use InetStudio\AdminPanel\Base\Contracts\Models\BaseModelContract;

/**
 * Interface TagModelContract.
 */
interface TagModelContract extends BaseModelContract, Auditable, HasMedia, MetableContract
{
}
