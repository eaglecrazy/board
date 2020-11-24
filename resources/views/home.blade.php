@extends('layouts.app')

@section('breadcrumbs', '')

@section('content')

    @auth
        @if(Auth::user()->isAdmin())
            <h2>Admin panel</h2>
            <ul class="nav nav-tabs mb-3">
                <li class="nav-item"><a href="{{ route('admin.adverts.adverts.index') }}" class="nav-link">Adverts</a></li>
                <li class="nav-item"><a href="{{ route('admin.users.index') }}" class="nav-link">Users</a></li>
                <li class="nav-item"><a href="{{ route('admin.regions.index') }}" class="nav-link">Regions</a></li>
                <li class="nav-item"><a href="{{ route('admin.adverts.categories.index') }}"
                                        class="nav-link">Categories</a></li>
            </ul>
        @endif

        <h2>User panel</h2>
        <ul class="nav nav-tabs mb-3">
            <li class="nav-item"><a class="nav-link active" href="{{ route('cabinet.home') }}">Dashboard</a></li>
            <li class="nav-item"><a class="nav-link" href="{{ route('cabinet.adverts.index') }}">Adverts</a></li>
            {{--    <li class="nav-item"><a class="nav-link" href="{{ route('cabinet.favorites.index') }}">Favorites</a></li>--}}
            {{--    <li class="nav-item"><a class="nav-link" href="{{ route('cabinet.banners.index') }}">Banners</a></li>--}}
            <li class="nav-item"><a class="nav-link" href="{{ route('cabinet.profile.home') }}">Profile</a></li>
            {{--    <li class="nav-item"><a class="nav-link" href="{{ route('cabinet.tickets.index') }}">Tickets</a></li>--}}
        </ul>
        <hr>
        <p><a href="{{ route('cabinet.adverts.create.category') }}" class="btn btn-success">Add Advert</a></p>
        <hr>
    @endauth
    <a href="{{ route('adverts.index') }}">All adverts</a>

    <div class="card card-default mb-3">
        <div class="card-header">
            All Categories
        </div>
        <div class="card-body pb-0" style="color: #aaa">
            <div class="row">
                @foreach (array_chunk($categories, 3) as $chunk)
                    <div class="col-md-3">
                        <ul class="list-unstyled">
                            @foreach ($chunk as $current)
                                <li>
                                  <a href="{{ route('adverts.index.all', $current) }}">{{ $current->name }}</a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <div class="card card-default mb-3">
        <div class="card-header">
            All Regions
        </div>
        <div class="card-body pb-0" style="color: #aaa">
            <div class="row">
                @foreach (array_chunk($regions, 3) as $chunk)
                    <div class="col-md-3">
                        <ul class="list-unstyled">
                            @foreach ($chunk as $current)
                                <li>
                                    <a href="{{ route('adverts.index', [$current, null]) }}">{{ $current->name }}</a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

@endsection
