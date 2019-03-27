@extends('admin::back.layouts.app')

@php
    $title = ($item->id) ? 'Редактирование тега' : 'Создание тега';
@endphp

@section('title', $title)

@section('content')

    @push('breadcrumbs')
        @include('admin.module.tags::back.partials.breadcrumbs.form')
    @endpush

    <div class="wrapper wrapper-content">
        <div class="ibox">
            <div class="ibox-title">
                <a class="btn btn-sm btn-white m-r-xs" href="{{ route('back.tags.index') }}">
                    <i class="fa fa-arrow-left"></i> Вернуться назад
                </a>
                @if ($item->id && $item->href)
                    <a class="btn btn-sm btn-white" href="{{ $item->href }}" target="_blank">
                        <i class="fa fa-eye"></i> Посмотреть на сайте
                    </a>
                @endif
            </div>
        </div>

        {!! Form::info() !!}

        {!! Form::open(['url' => (! $item->id) ? route('back.tags.store') : route('back.tags.update', [$item->id]), 'id' => 'mainForm', 'enctype' => 'multipart/form-data']) !!}

            @if ($item->id)
                {{ method_field('PUT') }}
            @endif

            {!! Form::hidden('tag_id', (! $item->id) ? '' : $item->id, ['id' => 'object-id']) !!}

            {!! Form::hidden('tag_type', get_class($item), ['id' => 'object-type']) !!}

            <div class="ibox">
                <div class="ibox-title">
                    {!! Form::buttons('', '', ['back' => 'back.tags.index']) !!}
                </div>
                <div class="ibox-content">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="panel-group float-e-margins" id="mainAccordion">
                                {!! Form::meta('', $item) !!}

                                {!! Form::social_meta('', $item) !!}

                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <h5 class="panel-title">
                                            <a data-toggle="collapse" data-parent="#mainAccordion" href="#collapseMain" aria-expanded="true">Основная информация</a>
                                        </h5>
                                    </div>
                                    <div id="collapseMain" class="collapse show" aria-expanded="true">
                                        <div class="panel-body">

                                            {!! Form::string('name', $item->name, [
                                                'label' => [
                                                    'title' => 'Название',
                                                ],
                                                'field' => [
                                                    'class' => 'form-control slugify',
                                                    'data-slug-url' => route('back.tags.getSlug'),
                                                    'data-slug-target' => 'slug',
                                                ],
                                            ]) !!}

                                            {!! Form::string('slug', $item->slug, [
                                                'label' => [
                                                    'title' => 'URL',
                                                ],
                                                'field' => [
                                                    'class' => 'form-control slugify',
                                                    'data-slug-url' => route('back.tags.getSlug'),
                                                    'data-slug-target' => 'slug',
                                                ],
                                            ]) !!}

                                            {!! Form::string('title', $item->title, [
                                                'label' => [
                                                    'title' => 'Заголовок',
                                                ],
                                            ]) !!}

                                            {!! Form::wysiwyg('content', $item->content, [
                                                'label' => [
                                                    'title' => 'Содержимое',
                                                ],
                                                'field' => [
                                                    'class' => 'tinymce',
                                                    'id' => 'content',
                                                    'hasImages' => true,
                                                ],
                                                'images' => [
                                                    'media' => $item->getMedia('content'),
                                                    'fields' => [
                                                        [
                                                            'title' => 'Описание',
                                                            'name' => 'description',
                                                        ],
                                                        [
                                                            'title' => 'Copyright',
                                                            'name' => 'copyright',
                                                        ],
                                                        [
                                                            'title' => 'Alt',
                                                            'name' => 'alt',
                                                        ],
                                                    ],
                                                ],
                                            ]) !!}

                                            {!! Form::tags('', $item, [
                                                'exclude' => [$item->id],
                                            ]) !!}

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="ibox-footer">
                    {!! Form::buttons('', '', ['back' => 'back.tags.index']) !!}
                </div>
            </div>

        {!! Form::close()!!}
    </div>
@endsection
