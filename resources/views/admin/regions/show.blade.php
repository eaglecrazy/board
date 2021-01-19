@php($pageTitle = 'Регион "' . $region->name . '"')
@extends('layouts.app')

@section('content')
    @include('admin.regions._nav')
    <div class="d-flex flex-row mb-3">
        <a href="{{ route('admin.regions.edit', $region) }}" class="btn btn-primary mr-1">Редактировать</a>
        <form method="POST" action="{{ route('admin.regions.destroy', $region) }}" class="mr-1">
            @csrf
            @method('DELETE')
            <button class="btn btn-danger">Удалить</button>
        </form>
    </div>

    <table class="table table-bordered table-striped">
        <tbody>
            <tr>
                <th>ID</th>
                <td>{{ $region->id }}</td>
            </tr>
            <tr>
                <th>Наименование</th>
                <td>{{ $region->name }}</td>
            </tr>
            <tr>
                <th>Слаг</th>
                <td>{{ $region->slug }}</td>
            </tr>
            @if($region->parent)
                <tr>
                    <th>Родительский регион</th>
                    <td><a href="{{ route('admin.regions.show', $region->parent) }}">{{ $region->parent->name }}</a></td>
                </tr>
            @endif
        </tbody>
    </table>
    <div class="d-flex flex-row mb-3">
        <a href="{{ route('admin.regions.create-inner', $region) }}" class="btn btn-primary mr-1">Создать внутренний регион</a>
    </div>

    @if(count($region->children))
        <h4>Внутренние регионы</h4>
        @include('admin.regions._list', ['regions' => $regions])
    @endif
@endsection
