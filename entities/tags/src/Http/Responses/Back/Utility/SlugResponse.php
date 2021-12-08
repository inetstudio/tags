<?php

namespace InetStudio\TagsPackage\Tags\Http\Responses\Back\Utility;

use InetStudio\TagsPackage\Tags\Contracts\Http\Responses\Back\Utility\SlugResponseContract;

class SlugResponse implements SlugResponseContract
{
    public function __construct(
        protected string $slug
    ) {}

    public function toResponse($request)
    {
        return response()->json($this->slug);
    }
}
