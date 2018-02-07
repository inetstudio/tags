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
    ],
    'options' => [
        'values' => (old('tags')) ? \InetStudio\Tags\Models\TagModel::whereIn('id', old('tags'))->pluck('name', 'id')->toArray() : $item->tags()->pluck('name', 'id')->toArray(),
    ],
]) !!}
