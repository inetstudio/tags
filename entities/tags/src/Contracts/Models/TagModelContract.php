<?php

namespace InetStudio\TagsPackage\Tags\Contracts\Models;

use OwenIt\Auditing\Contracts\Auditable;
use Spatie\MediaLibrary\HasMedia;
use InetStudio\AdminPanel\Base\Contracts\Models\BaseModelContract;

/**
 * Interface TagModelContract.
 */
interface TagModelContract extends BaseModelContract, Auditable, HasMedia
{
}
