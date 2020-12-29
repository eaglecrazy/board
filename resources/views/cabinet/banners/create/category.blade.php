@extends('layouts.app')

@section('content')
    @include('cabinet._nav', ['page' => 'banners'])
    <p>Выберите категорию:</p>
    @include('cabinet.banners.create._categories', ['categories' => $categories])
@endsection
