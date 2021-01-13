@php($pageTitle = 'Баннер "' . $banner->name . '"')
@extends('layouts.app')

@section('content')

    @if ($banner->reject_reason)
        <div class="alert alert-danger">
            Причина отклонения: {{ $banner->reject_reason }}
        </div>
    @endif

    @can ('manage-banners')
        <h3>Панель модератора</h3>
        <div class="d-flex flex-row mb-3">
            <a href="{{ route('admin.banners.edit', $banner) }}" class="btn btn-primary mr-1">Редактировать</a>
            @if ($banner->isOnModeration())
                <form method="POST" action="{{ route('admin.banners.moderate', $banner) }}" class="mr-1">
                    @csrf
                    <button class="btn btn-success">Одобрить</button>
                </form>
                <a href="{{ route('admin.banners.reject', $banner) }}" class="btn btn-warning mr-1">Отклонить</a>
            @endif

            @if ($banner->isOrdered())
                <form method="POST" action="{{ route('admin.banners.pay', $banner) }}" class="mr-1">
                    @csrf
                    <button class="btn btn-success">Отметить как оплаченный</button>
                </form>
            @endif
            <form method="POST" action="{{ route('admin.banners.destroy', $banner) }}" class="mr-1">
                @csrf
                @method('DELETE')
                <button class="btn btn-danger">Удалить</button>
            </form>
        </div>
    @endcan

    @can ('manage-own-banner', $banner)
        <h3>Управление баннером</h3>
        <div class="d-flex flex-row mb-3">
            @if ($banner->canBeChanged())
                <a href="{{ route('cabinet.banners.edit', $banner) }}" class="btn btn-primary mr-1">Редактировать</a>
                <a href="{{ route('cabinet.banners.edit_file', $banner) }}" class="btn btn-primary mr-1">Изменить
                    файл</a>
            @endif

            @if ($banner->isDraft())
                <form method="POST" action="{{ route('cabinet.banners.send', $banner) }}" class="mr-1">
                    @csrf
                    <button class="btn btn-success">Отправить на модерацию</button>
                </form>
            @endif

            @if ($banner->isOnModeration())
                <form method="POST" action="{{ route('cabinet.banners.cancel', $banner) }}" class="mr-1">
                    @csrf
                    <button class="btn btn-secondary">Отозвать с модерации</button>
                </form>
            @endif

            @if ($banner->isModerated())
                <form method="POST" action="{{ route('cabinet.banners.order', $banner) }}" class="mr-1">
                    @csrf
                    <button class="btn btn-success">Оплатить</button>
                </form>
            @endif

            @if ($banner->canBeRemoved())
                <form method="POST" action="{{ route('cabinet.banners.destroy', $banner) }}" class="mr-1">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-danger">Удалить</button>
                </form>
            @endif
        </div>
    @endcan

    <table class="table table-bordered table-striped">
        <tbody>
        <tr>
            <th>ID</th>
            <td>{{ $banner->id }}</td>
        </tr>
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
            <img src="{{ Storage::disk('public')->url($banner->file) }}"/>
        </div>
    </div>
@endsection
