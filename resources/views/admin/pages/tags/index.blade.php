@extends('admin::layouts.app')

@php
    $title = 'Теги';
@endphp

@section('title', $title)

@section('content')

    @include('admin.module.tags::partials.breadcrumb_index', ['title' => $title])

    <div class="wrapper wrapper-content">
        <div class="row">
            <div class="col-lg-12">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <a href="{{ route('back.tags.create') }}" class="btn btn-sm btn-primary btn-lg">Добавить</a>
                    </div>
                    <div class="ibox-content">
                        <div class="row m-b-md">
                            <div class="col-sm-offset-9 col-sm-3">
                                <form method="get" action="">
                                    <div class="input-group">
                                        <input type="text" name="search" placeholder="Поиск" class="input-sm form-control">
                                        <span class="input-group-btn"><button type="submit" class="btn btn-sm btn-primary">Найти</button> </span>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <table class="table">
                            <thead>
                            <tr>
                                <th>Название</th>
                                <th>Дата создания</th>
                                <th>Дата обновления</th>
                                <th>Действия</th>
                            </tr>
                            </thead>
                            <tbody>
                            @if(isset($items))
                                @foreach($items as $item)
                                    <tr>
                                        <td>{{ $item->name }}</td>
                                        <td>{{ $item->created_at }}</td>
                                        <td>{{ $item->updated_at }}</td>
                                        <td>
                                            <div class="btn-group">
                                                <a href="{{ route('back.tags.edit', [$item->id]) }}" class="btn btn-xs btn-default m-r"><i class="fa fa-pencil"></i></a>
                                                <a href="#" class="btn btn-xs btn-danger delete" data-url="{{ route('back.tags.destroy', [$item->id]) }}"><i class="fa fa-times"></i></a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                            </tbody>
                        </table>
                    </div>

                    {!! $links !!}

                </div>
            </div>
        </div>
    </div>
@endsection
