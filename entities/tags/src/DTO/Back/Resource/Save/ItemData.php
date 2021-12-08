<?php

namespace InetStudio\TagsPackage\Tags\DTO\Back\Resource\Save;

use Illuminate\Http\Request;
use Spatie\DataTransferObject\DataTransferObject;
use InetStudio\TagsPackage\Tags\Contracts\DTO\Back\Resource\Save\ItemDataContract;

class ItemData extends DataTransferObject implements ItemDataContract
{
    public ?int $id;

    public string $name;

    public string $slug;

    public ?string $title;

    public ?string $content;

    public static function fromRequest(Request $request): self
    {
        $content = $request->input('content', '');
        $content = (isset($content['text'])) ? $content['text'] : (! is_array($content) ? $content : '');
        $content = trim(str_replace('&nbsp;', ' ', $content));

        $data = [
            'id' => $request->input('id'),
            'name' => trim(strip_tags($request->input('name', ''))),
            'slug' => trim(strip_tags($request->input('slug', ''))),
            'title' => trim(strip_tags($request->input('title', ''))),
            'content' => $content,
        ];

        return new self($data);
    }
}
