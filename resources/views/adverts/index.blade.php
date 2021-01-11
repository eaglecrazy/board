{{--
Вывод всех объявлений (или объявлений по региону или категории)
127.0.0.1:8080/adverts
--}}

@extends('layouts.app')

@section('search')
    @include('layouts.partials.search', ['searchRoute' => '?'])
@endsection

@section('content')
    @if ($childernRegions)
        <div class="card card-default mb-3">
            <div class="card-header h4">
                @if ($path->region)
                    Регионы {{ $path->region->name }}
                @else
                    Все регионы
                @endif
            </div>
            <div class="card-body pb-0" style="color: #aaa">
                <div class="row">
                    @foreach (array_chunk($childernRegions, 3) as $chunk)
                        <div class="col-md-3">
                            <ul class="list-unstyled">
                                @foreach ($chunk as $current)
                                    <li>
                                        <a href="{{ route('adverts.index',  array_merge([$path->getRegionUrl($current)], request()->input())) }}">{{ $current->name }}</a>
                                        ({{ $regionsCounts[$current->id] ?? 0 }})
                                    </li>
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
            <div class="card-header h4">
                @if ($path->category)
                    Категории {{ $path->category->name }}
                @else
                    Все категории
                @endif
            </div>
            <div class="card-body pb-0" style="color: #aaa">
                <div class="row">
                    @foreach (array_chunk($childernCategories, 3) as $chunk)
                        <div class="col-md-3">
                            <ul class="list-unstyled">
                                @foreach ($chunk as $current)
                                    <li>
                                        <a href="{{ route('adverts.index',  array_merge([$path->getCategoryUrl($current)], request()->input())) }}">{{ $current->name }}</a>
                                        ({{ $categoriesCounts[$current->id] ?? 0 }})
                                    </li>
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
                            <div class="col-md-4">
                                <div class="advert-photo">
                                    <img src="{{ asset('storage/') . '/' .  $photos[$advert->id] }}" height="180">
                                </div>
                            </div>
                            <div class="col-md-8">
                                <span class="float-right font-weight-bold h5">{{ $advert->price }} руб.</span>
                                <div class="h4" style="margin-top: 0"><a
                                        href="{{ route('adverts.show', $advert) }}">{{ $advert->title }}</a></div>
                                <p><span class="font-weight-bold">Регион:</span> <a
                                        href="{{ route('adverts.index', adPath($advert->region, $path->category)) }}">{{ $advert->region ? $advert->region->name : 'All regions' }}</a>
                                </p>
                                <p><span class="font-weight-bold">Категория:</span> <a
                                        href="{{ route('adverts.index', adPath($path->region, $advert->category)) }}">{{ $advert->category->name }}</a>
                                </p>
                                <p><span class="font-weight-bold">Дата публикации:</span> {{ $advert->created_at }}</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            {{ $adverts->links() }}
        </div>
        <div class="col-md-3 banner"
                 data-format="240x400"
                 data-category="{{ $path->category ? $path->category->id : '' }}"
                 data-region="{{ $path->region ? $path->region->id : '' }}" data-url="{{ route('banner.get') }}"></div>
        </div>
    </div>

@endsection
