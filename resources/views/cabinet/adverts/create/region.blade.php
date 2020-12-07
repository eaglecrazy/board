@extends('layouts.app')

@section('content')
    @include('cabinet._nav', ['page' => 'adverts'])

    <p>Choose region</p>
    @if($region)
        <p><a href="{{ route('cabinet.adverts.create.advert', [$category, $region]) }}" class="btn btn-success">Add
                advert for {{ $region->name }}</a></p>
    @else
        <p><a href="{{ route('cabinet.adverts.create.advert', [$category]) }}" class="btn btn-success">Add advert for
                all regions</a></p>
    @endif

    @if($regions->count())
        <p>Or choose nested region</p>
        <ul>
            @foreach($regions as $current_region)
                <li>
                    <a href="{{ route('cabinet.adverts.create.region', [$category, $current_region]) }}">{{ $current_region->name }}</a>
                </li>
            @endforeach
        </ul>
    @endif
@endsection
