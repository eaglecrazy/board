@extends('layouts.app')

@section('breadcrumbs', '')

@section('content')

    @can('admin-panel')
        <h2>Панель администратора</h2>
        @include('admin._nav', ['page' => ''])
    @endcan

    @auth
        <h2>Панель пользователя</h2>
        @include('cabinet._nav', ['page' => ''])
    @endauth
    <a class="page-link h2" href="{{ route('adverts.index') }}">Все объявления</a>
    <hr>
    <div class="card card-default mb-3">
        <div class="card-header h4">Регионы</div>
        <div class="card-body pb-0" style="color: #aaa">
            <div class="row">
                @foreach (array_chunk($regions, 3) as $chunk)
                    <div class="col-md-3">
                        <ul class="list-unstyled">
                            @foreach ($chunk as $current)
                                <li>
                                    <a href="{{ route('adverts.index',  adPath($current, null)) }}">{{ $current->name }}</a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    <div class="card card-default mb-3">
        <div class="card-header h4">Категории</div>
        <div class="card-body pb-0" style="color: #aaa">
            <div class="row">
                @foreach (array_chunk($categories, 3) as $chunk)
                    <div class="col-md-3">
                        <ul class="list-unstyled">
                            @foreach ($chunk as $current)
                                <li>
                                    <a href="{{ route('adverts.index',  adPath(null, $current)) }}">{{ $current->name }}</a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection
