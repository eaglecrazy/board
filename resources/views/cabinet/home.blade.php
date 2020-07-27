@extends('layouts.app')

@section('content')
    @include('cabinet._nav')
    <ul>
{{--        <li class="nav-item"><a href="{{ route('cabinet.home') }}" class="navlink active">Dashboard</a></li>--}}
        <li class="nav-item"><a href="{{ route('cabinet.profile.home') }}" class="navlink">Profile</a></li>
    </ul>
@endsection
