@extends('layouts.app')

@section('content')
    @include('cabinet._nav', ['page' => 'banners'])

    <p><a href="{{ route('cabinet.banners.create') }}" class="btn btn-success">Добавить баннер</a></p>

    <table class="table table-striped">
        <thead>
        <tr>
            <th>ID</th>
            <th>Название</th>
            <th>Регион</th>
            <th>Категория</th>
            <th>Публикация</th>
            <th>Статус</th>
        </tr>
        </thead>
        <tbody>

        @foreach ($banners as $banner)
            <tr>
                <td>{{ $banner->id }}</td>
                <td><a href="{{ route('cabinet.banners.show', $banner) }}" target="_blank">{{ $banner->name }}</a></td>
                <td>
                    @if ($banner->region)
                        {{ $banner->region->name }}
                    @endif
                </td>
                <td>{{ $banner->category->name }}</td>
                <td>{{ $banner->published_at }}</td>
                <td>
                    @if ($banner->isDraft())
                        <span class="badge badge-secondary">Черновик</span>
                    @elseif ($banner->isOnModeration())
                        <span class="badge badge-primary">Модерация</span>
                    @elseif ($banner->isModerated())
                        <span class="badge badge-success">Готов к оплате</span>
                    @elseif ($banner->isOrdered())
                        <span class="badge badge-warning">Ожидает оплаты</span>
                    @elseif ($banner->isActive())
                        <span class="badge badge-primary">Активный</span>
                    @elseif ($banner->isClosed())
                        <span class="badge badge-secondary">Закрыт</span>
                    @endif
                </td>
            </tr>
        @endforeach

        </tbody>
    </table>

{{--    {{ $banners->links() }}--}}
@endsection
