@php
    $title = ($item->id) ? 'Редактирование тега' : 'Добавление тега';
@endphp

@extends('admin::layouts.app')

@section('title', $title)

@section('styles')
    <!-- CROPPER -->
    <link href="{!! asset('admin/css/plugins/cropper/cropper.min.css') !!}" rel="stylesheet">

    <!-- ICHECK -->
    <link href="{!! asset('admin/css/plugins/iCheck/custom.css') !!}" rel="stylesheet">

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

            {!! Form::meta('', $item) !!}

            {!! Form::social_meta('', $item) !!}

            <div class="row">
                <div class="col-lg-12">
                    <div class="panel-group float-e-margins" id="mainAccordion">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h5 class="panel-title">
                                    <a data-toggle="collapse" data-parent="#mainAccordion" href="#collapseMain" aria-expanded="true">Основная информация</a>
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

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {!! Form::buttons('', '', ['back' => 'back.tags.index']) !!}

        {!! Form::close()!!}
    </div>

    {!! Form::modals_crop() !!}

    {!! Form::modals_uploader('', '', '') !!}

    {!! Form::modals_edit_image('', '', '') !!}

@endsection

@section('scripts')
    <!-- CROPPER -->
    <script src="{!! asset('admin/js/plugins/cropper/cropper.min.js') !!}"></script>

    <!-- ICHECK -->
    <script src="{!! asset('admin/js/plugins/iCheck/icheck.min.js') !!}"></script>

    <!-- PLUPLOAD -->
    <script src="{!! asset('admin/js/plugins/plupload/plupload.full.min.js') !!}"></script>

    <!-- SELECT2 -->
    <script src="{!! asset('admin/js/plugins/select2/select2.full.min.js') !!}"></script>
    <script src="{!! asset('admin/js/plugins/select2/i18n/ru.js') !!}"></script>

    <!-- TINYMCE -->
    <script src="{!! asset('admin/js/plugins/tinymce/tinymce.min.js') !!}"></script>
@endsection
