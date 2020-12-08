@extends('layouts.app')

@section('content')
    @include('admin.banners._nav')

    <div class="d-flex flex-row mb-3">

        <a href="{{ route('admin.banners.edit', $banner) }}" class="btn btn-primary mr-1">Редактировать</a>

        @if ($banner->isOnModeration())
            <form method="POST" action="{{ route('admin.banners.moderate', $banner) }}" class="mr-1">
                @csrf
                <button class="btn btn-success">Одобрить</button>
            </form>
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

    <table class="table table-bordered table-striped">
        <tbody>
        <tr>
            <th>ID</th>
            <td>{{ $banner->id }}</td>
        </tr>
        <tr>
            <th>Название</th>
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
            <th>Дата публикации</th>
            <td>{{ $banner->published_at }}</td>
        </tr>
        </tbody>
    </table>

    <div class="card">
        <div class="card-body">
            <img src="{{ asset('storage/') . $banner->file }}"/>
            {{--            <img src="{{ Storage::disk('public')->url($banner->file) }}" />--}}
        </div>
    </div>
@endsection
