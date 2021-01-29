@php($pageTitle = 'Мои баннеры')
@extends('layouts.app')

@section('content')
    @include('cabinet._nav', ['page' => 'banners'])

    <p><a href="{{ route('cabinet.banners.create') }}" class="btn btn-success">Добавить баннер</a></p>

    <table class="table table-striped">
        <thead>
        <tr>
            <th>Название</th>
            <th>Регион</th>
            <th>Категория</th>
            <th>Дата публикации</th>
            <th>Статус</th>
        </tr>
        </thead>
        <tbody>

        @foreach ($banners as $banner)
            <tr>
                <td><a href="{{ route('cabinet.banners.show', $banner) }}" target="_blank">{{ $banner->name }}</a></td>
                <td>
                    @if ($banner->region)
                        {{ $banner->region->name }}
                    @endif
                </td>
                <td>{{ $banner->category->name }}</td>
                <td>{{ isset($banner->published_at) ? dtFormat($banner->published_at) : '' }}</td>
                <td>
                    @include('cabinet.banners._banners_bages')
                </td>
            </tr>
        @endforeach

        </tbody>
    </table>

        {{ $banners->links() }}
@endsection
