@php($pageTitle = 'Избранное')
@extends('layouts.app')

@section('content')
    @include('cabinet._nav', ['page' => 'favorites'])
    <table class="table table-striped">
        <thead>
        <tr>
            <th>Наименование</th>
            <th>Регион</th>
            <th>Категория</th>
            <th>Изменено</th>
            <th></th>
        </tr>
        </thead>
        <tbody>

        @foreach ($adverts as $advert)
            <tr>
                <td><a href="{{ route('adverts.show', $advert) }}" target="_blank">{{ $advert->title }}</a></td>
                <td>
                    @if ($advert->region)
                        {{ $advert->region->name }}
                    @endif
                </td>
                <td>{{ $advert->category->name }}</td>
                <td>{{ $advert->updated_at }}</td>
                <td>
                    <form method="POST" action="{{ route('cabinet.favorites.remove', $advert) }}" class="mr-1">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-sm btn-danger"><span class="fa fa-remove"></span> Удалить</button>
                    </form>
                </td>
            </tr>
        @endforeach

        </tbody>
    </table>

    {{ $adverts->links() }}
@endsection
