<?php

namespace InetStudio\TagsPackage\Tags\Contracts\Services\Back;

use InetStudio\TagsPackage\Tags\Contracts\Models\TagModelContract;
use InetStudio\TagsPackage\Tags\Contracts\DTO\Back\Resource\Save\ItemDataContract;
use InetStudio\TagsPackage\Tags\Contracts\Services\ItemsServiceContract as BaseItemsServiceContract;

interface ResourceServiceContract extends BaseItemsServiceContract
{
    public function create(): TagModelContract;

    public function show(int|string $id): TagModelContract;

    public function save(ItemDataContract $data): TagModelContract;

    public function destroy(int|string $id): int;
}
