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

    <h2>Adverts</h2>

{{--    <div class="region-selector"--}}
{{--         data-selected="{{ json_encode((array)old('regions')) }}"--}}
{{--         data-source="{{ route('ajax.regions') }}">--}}
{{--        <div class="form-group">--}}
{{--            <select class="form-control region-select" data-level="1">--}}
{{--                <option value=""></option>--}}
{{--            </select>--}}
{{--        </div>--}}
{{--    </div>--}}

@endsection
