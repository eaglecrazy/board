@php($pageTitle = 'Атрибут "' . $attribute->name . '"')
@extends('layouts.app')

@section('content')
    @include('admin.adverts.categories._nav')
    <div class="d-flex flex-row mb-3">
        <a href="{{ route('admin.adverts.categories.attributes.edit', [$category, $attribute]) }}" class="btn btn-primary mr-1">Редактировать</a>
        <form method="POST" action="{{ route('admin.adverts.categories.attributes.destroy', [$category, $attribute]) }}" class="mr-1">
            @csrf
            @method('DELETE')
            <button class="btn btn-danger">Удалить</button>
        </form>
    </div>

    <table class="table table-bordered table-striped">
        <tbody>
        <tr>
            <th>ID</th>
            <td>{{ $attribute->id }}</td>
        </tr>
        <tr>
            <th>Наименование</th>
            <td>{{ $attribute->name }}</td>
        </tr>
        <tr>
            <th>Тип атрибута</th>
            <td>{{ $attribute::typesList()[$attribute->type] }}</td>
        </tr>
        <tr>
            <th>Обязательный атрибут</th>
            <td>{!! $attribute->required ? '&#10004;' : '' !!}</td>
        </tr>
        <tr>
            <th>Значение по умолчанию</th>
            <td>{{ $attribute->default }}</td>
        </tr>
        <tr>
            <th>Варианты выбора</th>
            <td>@foreach($attribute->variants as $variant) {{ $variant }}<br>@endforeach</td>

        </tr>
        <tr>
            <th>Сортировка</th>
            <td>{{ $attribute->sort }}</td>
        </tr>
        <tr>
            <th>Категория</th>
            <td>
                <a href="{{ route('admin.adverts.categories.show', $category) }}">{{ $category->name }}</a>
            </td>
        </tr>
        </tbody>
    </table>
@endsection
