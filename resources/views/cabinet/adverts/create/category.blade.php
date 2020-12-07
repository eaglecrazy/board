@extends('layouts.app')

@section('content')
    @include('cabinet._nav', ['page' => 'adverts'])
    <p>Выберите категорию</p>
    @include('cabinet.adverts.create._categories', ['categories' => $categories])
@endsection
