<?php

namespace InetStudio\TagsPackage\Tags\Contracts\Models;

use Spatie\MediaLibrary\HasMedia;
use OwenIt\Auditing\Contracts\Auditable;

interface TagModelContract extends Auditable, HasMedia
{
}
