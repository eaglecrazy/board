@php($pageTitle = 'Выбор региона')
@extends('layouts.app')

@section('content')
    @include('cabinet._nav', ['page' => 'adverts'])
    <div class="card">
        <div class="card-header h4">Выбор региона</div>
        <div class="card-body p-2">
            @if($region)
                <a class="btn btn-success m-2"
                   href="{{ route('cabinet.adverts.create.advert', [$category, $region]) }}">Добавить
                    объявление для региона {{ $region->name }}</a>
            @else
                <a class="my-2 ml-2 btn btn-success" href="{{ route('cabinet.adverts.create.advert', [$category]) }}">Добавить
                    объявление для всех регионов</a>
                @foreach ($importantRegions as $importantRegion)
                    <a class="my-2 ml-2 btn btn-primary"
                       href="{{ route('cabinet.adverts.create.region', [$category, $importantRegion]) }}">{{ $importantRegion->name }}</a>
                @endforeach
            @endif
        </div>
    </div>
    @if($innerRegions->count())
        <div class="card">
            <div class="card-header h4">Или выбрать дочерний регион:</div>
            <div class="card-body p-3">
                <ul>
                    @foreach($innerRegions as $current_region)
                        <li>
                            <a href="{{ route('cabinet.adverts.create.region', [$category, $current_region]) }}">{{ $current_region->name }}</a>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif
@endsection
