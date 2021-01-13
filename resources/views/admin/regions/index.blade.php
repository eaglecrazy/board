@php($pageTitle = 'Управление регионами')
@extends('layouts.app')

@section('content')
    @include('admin.regions._nav')
    <div class="d-flex flex-row mb-3">
        <a href="{{ route('admin.regions.create') }}" class="btn btn-primary mr-1">Create region</a>
    </div>
    @include('admin.regions._list', ['regions' => $regions])
@endsection
