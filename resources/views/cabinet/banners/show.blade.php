@php($pageTitle = 'Баннер "' . $banner->name . '"')
@extends('layouts.app')

@section('content')
    @include('cabinet._nav', ['page'=>'banners'])
    @if ($banner->isDraft() && $banner->reject_reason)
        <div class="alert alert-danger">
            Модерация не пройдена. Причина отклонения: {{ $banner->reject_reason }}
        </div>
    @endif

    @can ('manage-banners', $banner)
        @include('cabinet.banners._moderator_panel')
    @endcan

    @can ('manage-own-banner', $banner)
        @if(!$banner->isActive())
            @include('cabinet.banners._owner_panel')
        @endif
    @endcan

    <table class="table table-bordered table-striped">
        <tbody>
        <tr>
            <th>Наименование</th>
            <td>{{ $banner->name }}</td>
        </tr>
        <tr>
            <th>Регион</th>
            <td>
                @if ($banner->region)
                    {{ $banner->region->name }}
                @endif
            </td>
        </tr>
        <tr>
            <th>Категория</th>
            <td>{{ $banner->category->name }}</td>
        </tr>
        <tr>
            <th>Статус</th>
            <td>
                @include('cabinet.banners._banners_bages')
            </td>
        </tr>
        <tr>
            <th>URL</th>
            <td><a href="{{ $banner->url }}">{{ $banner->url }}</a></td>
        </tr>
        <tr>
            <th>Лимит</th>
            <td>{{ $banner->limit }}</td>
        </tr>
        <tr>
            <th>Просмотры</th>
            <td>{{ $banner->views }}</td>
        </tr>
        <tr>
            <th>Дата публикации</th>
            <td>{{ $banner->published_at }}</td>
        </tr>
        </tbody>
    </table>

    <div class="card">
        <div class="card-body">
            <img class="main-photo" src="{{ asset('storage/') . '/' .  $banner->file}}" width="240" height="400"/>
        </div>
    </div>
@endsection
