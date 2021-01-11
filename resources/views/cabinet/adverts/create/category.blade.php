@extends('layouts.app')

@section('content')
    @include('cabinet._nav', ['page' => 'adverts'])
    <div class="card">
        <div class="card-header h4">Выбор категории</div>
        <div class="card-body p-3">
            @include('cabinet.adverts.create._categories', ['categories' => $categories])
        </div>
    </div>
@endsection
