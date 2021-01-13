@php($pageTitle = 'Фотобарахолка №1')
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
    <div id="accordion">
        <div class="card">
            <div class="card-header page-link h4 cursor-pointer" id="headingOne" data-toggle="collapse"
                 data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                Объявления по регионам:
            </div>
            <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordion">
                <div class="card-body pb-0">
                    <div class="row">
                        @foreach ($importantRegions as $importantRegion)
                            <div class="col-md-3">
                                <ul class="list-unstyled">
                                    <li>
                                        <a class="font-weight-bold text-dark"
                                           href="{{ route('adverts.index',  adPath($importantRegion, null)) }}">{{ $importantRegion->name }}</a>
                                    </li>
                                </ul>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
            <div class="card-header page-link h6 cursor-pointer collapsed" id="headingTwo" data-toggle="collapse"
                 data-target="#collapseTwo" aria-expanded="true" aria-controls="collapseTwo">
                &#8659; Показать все регионы &#8659;
            </div>
            <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordion">
                <div class="card-body pb-0" style="color: #aaa">
                    <div class="row">
                        @foreach (array_chunk($regions, (count($regions) / 2) + 1) as $chunk)
                            <div class="col-md-6">
                                <ul class="list-unstyled">
                                    @foreach ($chunk as $current)
                                        <li>
                                            <a class="text-dark" href="{{ route('adverts.index',  adPath($current, null)) }}">{{ $current->name }}</a>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card card-default mb-3 mt-3">
        <div class="card-header h4">Объявления по категориям:</div>
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
