@extends('layouts.app')

@section('content')

    <ul class="nav nav-tabs mb-3">
        <li class="nav-item"><a class="nav-link " href="{{ route('cabinet.home') }}">Dashboard</a></li>
        <li class="nav-item"><a class="nav-link active" href="{{ route('cabinet.adverts.index') }}">Adverts</a></li>
        {{--    <li class="nav-item"><a class="nav-link" href="{{ route('cabinet.favorites.index') }}">Favorites</a></li>--}}
        {{--    <li class="nav-item"><a class="nav-link" href="{{ route('cabinet.banners.index') }}">Banners</a></li>--}}
        <li class="nav-item"><a class="nav-link" href="{{ route('cabinet.profile.home') }}">Profile</a></li>
        {{--    <li class="nav-item"><a class="nav-link" href="{{ route('cabinet.tickets.index') }}">Tickets</a></li>--}}
    </ul>

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
