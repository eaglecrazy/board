@extends('layouts.app')

@section('content')
    @include('cabinet._nav', ['page' => 'banners'])

    <div class="d-flex flex-row mb-3">

        @if ($banner->canBeChanged())
            <a href="{{ route('cabinet.banners.edit', $banner) }}" class="btn btn-primary mr-1">Редактировать</a>
            <a href="{{ route('cabinet.banners.file', $banner) }}" class="btn btn-primary mr-1">Изменить файл</a>
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
            <th>Name</th>
            <td>{{ $banner->name }}</td>
        </tr>
        <tr>
            <th>Region</th>
            <td>
                @if ($banner->region)
                    {{ $banner->region->name }}
                @endif
            </td>
        </tr>
        <tr>
            <th>Category</th>
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
            <img src="{{ asset('storage/') . $banner->file }}"/>
{{--            <img src="{{ Storage::disk('public')->url($banner->file) }}"/>--}}
        </div>
    </div>
@endsection
