@php
    $title = ($item->id) ? 'Редактирование тега' : 'Добавление тега';
@endphp

@extends('admin::layouts.app')

@section('title', $title)

@section('styles')
    <!-- SELECT2 -->
    <link href="{!! asset('admin/css/plugins/select2/select2.min.css') !!}" rel="stylesheet">
@endsection

@section('content')
    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="col-sm-12">
            <h2>
                {{ $title }}
            </h2>
            <ol class="breadcrumb">
                <li>
                    <a href="{{ url('/back/') }}">Главная</a>
                </li>
                <li>
                    <a href="{{ route('back.tags.index') }}">Теги</a>
                </li>
                <li class="active">
                    <strong>
                        {{ $title }}
                    </strong>
                </li>
            </ol>
        </div>
    </div>

    <div class="wrapper wrapper-content">

        {!! Form::info() !!}

        {!! Form::open(['url' => (!$item->id) ? route('back.tags.store') : route('back.tags.update', [$item->id]), 'id' => 'mainForm', 'enctype' => 'multipart/form-data', 'class' => 'form-horizontal']) !!}

            @if ($item->id)
                {{ method_field('PUT') }}
            @endif

            {!! Form::hidden('tag_id', (!$item->id) ? "" : $item->id) !!}

            <div class="row">
                <div class="col-lg-12">
                    <div class="panel-group float-e-margins" id="metaAccordion">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h5 class="panel-title">
                                    <a data-toggle="collapse" data-parent="#metaAccordion" href="#collapseMeta" aria-expanded="false" class="collapsed">Мета теги</a>
                                </h5>
                            </div>
                            <div id="collapseMeta" class="panel-collapse collapse" aria-expanded="false">
                                <div class="panel-body">

                                    {!! Form::string('meta[title]', $item->getMeta('title'), [
                                        'label' => [
                                            'title' => 'Title',
                                            'class' => 'col-sm-2 control-label',
                                        ],
                                        'field' => [
                                            'class' => 'form-control',
                                        ],
                                    ]) !!}

                                    {!! Form::string('meta[description]', $item->getMeta('description'), [
                                        'label' => [
                                            'title' => 'Description',
                                            'class' => 'col-sm-2 control-label',
                                        ],
                                        'field' => [
                                            'class' => 'form-control',
                                        ],
                                    ]) !!}

                                    {!! Form::string('meta[keywords]', $item->getMeta('keywords'), [
                                        'label' => [
                                            'title' => 'Keywords',
                                            'class' => 'col-sm-2 control-label',
                                        ],
                                        'field' => [
                                            'class' => 'form-control',
                                        ],
                                    ]) !!}

                                    {!! Form::radios('meta[robots]', $item->getMeta('robots'), [
                                        'label' => [
                                            'title' => 'Индексировать',
                                            'class' => 'col-sm-2 control-label',
                                        ],
                                        'radios' => [
                                            [
                                                'label' => 'Да',
                                                'value' => 'index, follow',
                                            ],
                                            [
                                                'label' => 'Нет',
                                                'value' => 'noindex, nofollow',
                                            ],
                                        ],
                                    ]) !!}

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <div class="panel-group float-e-margins" id="socialMetaAccordion">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h5 class="panel-title">
                                    <a data-toggle="collapse" data-parent="#socialMetaAccordion" href="#collapseSocialMeta" aria-expanded="false" class="collapsed">Социальные мета теги</a>
                                </h5>
                            </div>
                            <div id="collapseSocialMeta" class="panel-collapse collapse" aria-expanded="false">
                                <div class="panel-body">

                                    {!! Form::string('meta[og:title]', $item->getMeta('og:title'), [
                                        'label' => [
                                            'title' => 'og:title',
                                            'class' => 'col-sm-2 control-label',
                                        ],
                                        'field' => [
                                            'class' => 'form-control',
                                        ],
                                    ]) !!}

                                    {!! Form::string('meta[og:description]', $item->getMeta('og:description'), [
                                        'label' => [
                                            'title' => 'og:description',
                                            'class' => 'col-sm-2 control-label',
                                        ],
                                        'field' => [
                                            'class' => 'form-control',
                                        ],
                                    ]) !!}

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <div class="panel-group float-e-margins" id="mainAccordion">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h5 class="panel-title">
                                    <a data-toggle="collapse" data-parent="#mainAccordion" href="#collapseMain" aria-expanded="true">Общая информация</a>
                                </h5>
                            </div>
                            <div id="collapseMain" class="panel-collapse collapse in" aria-expanded="true">
                                <div class="panel-body">

                                    {!! Form::string('name', $item->name, [
                                        'label' => [
                                            'title' => 'Название',
                                            'class' => 'col-sm-2 control-label',
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
                                            'class' => 'col-sm-2 control-label',
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
                                            'class' => 'col-sm-2 control-label',
                                        ],
                                        'field' => [
                                            'class' => 'form-control',
                                        ],
                                    ]) !!}

                                    {!! Form::wysiwyg('content', $item->content, [
                                        'label' => [
                                            'title' => 'Содержимое',
                                            'class' => 'col-sm-2 control-label',
                                        ],
                                        'field' => [
                                            'class' => 'tinymce',
                                        ],
                                    ]) !!}

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {!! Form::buttons('', '', ['back' => 'back.tags.index']) !!}

        {!! Form::close()!!}

    </div>
@endsection

@section('scripts')
    <!-- SELECT2 -->
    <script src="{!! asset('admin/js/plugins/select2/select2.full.min.js') !!}"></script>
    <script src="{!! asset('admin/js/plugins/select2/i18n/ru.js') !!}"></script>

    <!-- TINYMCE -->
    <script src="{!! asset('admin/js/plugins/tinymce/tinymce.min.js') !!}"></script>
@endsection
