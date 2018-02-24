@inject('tagsService', 'InetStudio\Tags\Contracts\Services\Back\TagsServiceContract')

@php
    $item = $value;
@endphp

{!! Form::dropdown('tags[]', $item->tags()->pluck('id')->toArray(), [
    'label' => [
        'title' => 'Теги',
    ],
    'field' => [
        'class' => 'select2 form-control',
        'data-placeholder' => 'Выберите теги',
        'style' => 'width: 100%',
        'multiple' => 'multiple',
        'data-source' => route('back.tags.getSuggestions'),
        'data-exclude' => isset($attributes['exclude']) ? implode('|', $attributes['exclude']) : '',
    ],
    'options' => [
        'values' => (old('tags')) ? $tagsService->getTagsByIDs(old('tags'), true)->pluck('name', 'id')->toArray() : $item->tags()->pluck('name', 'id')->toArray(),
    ],
]) !!}
