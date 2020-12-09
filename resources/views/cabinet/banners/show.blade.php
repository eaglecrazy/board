@extends('layouts.app')

@section('content')
    @include('cabinet._nav', ['page' => 'banners'])

    @can ('manage-adverts', $advert)
        <h3>Moderation access</h3>
        <div class="d-flex flex-row mb-3">
            <a href="{{ route('admin.adverts.adverts.edit', $advert) }}" class="btn btn-primary mr-1">Edit</a>
            <a href="{{ route('admin.adverts.adverts.photos', $advert) }}" class="btn btn-primary mr-1">Photos</a>
            @if ($advert->isModeration())
                <form method="POST" action="{{ route('admin.adverts.adverts.moderate', $advert) }}" class="mr-1">
                    @csrf
                    <button class="btn btn-success">Moderate</button>
                </form>
            @endif

            @if ($advert->isModeration() || $advert->isActive())
                <a href="{{ route('admin.adverts.adverts.reject', $advert) }}" class="btn btn-danger mr-1">Reject</a>
            @endif

            <form method="POST" action="{{ route('admin.adverts.adverts.destroy', $advert) }}" class="mr-1">
                @csrf
                @method('DELETE')
                <button class="btn btn-danger">Delete</button>
            </form>
        </div>
    @endcan

    <div class="d-flex flex-row mb-3">
        @if ($banner->canBeChanged())
            <a href="{{ route('cabinet.banners.edit', $banner) }}" class="btn btn-primary mr-1">Редактировать</a>
            <a href="{{ route('cabinet.banners.edit_file', $banner) }}" class="btn btn-primary mr-1">Изменить файл</a>
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
            <img src="{{ asset('storage/') . '/' . $banner->file }}"/>
{{--            <img src="{{ Storage::disk('public')->url($banner->file) }}"/>--}}
        </div>
    </div>
@endsection
