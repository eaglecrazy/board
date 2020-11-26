{{--
Вывод всех объявлений (или объявлений по региону или категории)
127.0.0.1:8080/adverts
--}}

@extends('layouts.app')

{{--@section('search')--}}
{{--    @include('layouts.partials.search', ['category' => $category, 'route' => '?'])--}}
{{--@endsection--}}

@section('content')

    <p><a href="{{ route('cabinet.adverts.create.category') }}" class="btn btn-success">Add Advert</a></p>

    @if ($childernRegions)
        <div class="card card-default mb-3">
            <div class="card-header">
                @if ($path->region)
                    Regions of {{ $path->region->name }}
                @else
                    Regions
                @endif
            </div>
            <div class="card-body pb-0" style="color: #aaa">
                <div class="row">
                    @foreach (array_chunk($childernRegions, 3) as $chunk)
                        <div class="col-md-3">
                            <ul class="list-unstyled">
                                @foreach ($chunk as $current)
                                    <li><a href="{{ route('adverts.index',  $path->getRegionUrl($current)) }}">{{ $current->name }}</a></li>
                                @endforeach
                            </ul>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif

    @if ($childernCategories)
        <div class="card card-default mb-3">
            <div class="card-header">
                @if ($path->category)
                    Categories of {{ $path->category->name }}
                @else
                    Categories
                @endif
            </div>
            <div class="card-body pb-0" style="color: #aaa">
                <div class="row">
                    @foreach (array_chunk($childernCategories, 3) as $chunk)
                        <div class="col-md-3">
                            <ul class="list-unstyled">
                                @foreach ($chunk as $current)
                                    <li><a href="{{ route('adverts.index',  $path->getCategoryUrl($current)) }}">{{ $current->name }}</a></li>
                                @endforeach
                            </ul>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif

    <div class="row">
        <div class="col-md-9">
            <div class="adverts-list">
                @foreach ($adverts as $advert)
                    <div class="advert">
                        <div class="row">
                            <div class="col-md-3">
                                <div style="height: 180px; background: #f6f6f6; border: 1px solid #ddd"></div>
                            </div>
                            <div class="col-md-9">
                                <span class="float-right">{{ $advert->price }}</span>
                                <div class="h4" style="margin-top: 0"><a href="{{ route('adverts.show', $advert) }}">{{ $advert->title }}</a></div>
                                <p>Регион: <a href="{{ route('adverts.index', adPath($advert)) }}">{{ $advert->region ? $advert->region->name : 'All regions' }}</a></p>
                                <p>Категория: <a href="{{ route('adverts.index', adPath($advert)) }}">{{ $advert->category->name }}</a></p>
                                <p>Date: {{ $advert->created_at }}</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            {{ $adverts->links() }}
        </div>
{{--        <div class="col-md-3">--}}
{{--            <div--}}
{{--                class="banner mb-3"--}}
{{--                data-url="{{ route('banner.get') }}"--}}
{{--                data-format="240x400"--}}
{{--                data-category="{{ $category ? $category->id : '' }}"--}}
{{--                data-region="{{ $region ? $region->id : '' }}"--}}
{{--            ></div>--}}
{{--        </div>--}}
    </div>

@endsection
