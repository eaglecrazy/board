@extends('layouts.app')

@section('content')
    @include('cabinet._nav', ['page' => 'banners'])

    @if ($region)
        <p>
            <a href="{{ route('cabinet.banners.create.banner', [$category, $region]) }}" class="btn btn-success">Добавить баннер для региона: {{ $region->name }}</a>
        </p>
    @else
        <p>
            <a href="{{ route('cabinet.banners.create.banner', [$category]) }}" class="btn btn-success">Добавить баннер для всех регионов</a>
        </p>
    @endif

    <p>Или выберите вложенный регион:</p>

    <ul>
        @foreach ($innerRegions as $current)
            <li>
                <a href="{{ route('cabinet.banners.create.region', [$category, $current]) }}">{{ $current->name }}</a>
            </li>
        @endforeach
    </ul>
@endsection
