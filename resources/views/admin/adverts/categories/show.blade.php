@php($pageTitle = 'Категория "' . $category->name . '"')
@extends('layouts.app')

@section('content')
    @include('admin.adverts.categories._nav')
    <div class="card p-3 mb-3">
        <h2>Категория "{{ $category->name }}"</h2>
        <div class="d-flex flex-row mb-3">
            <a href="{{ route('admin.adverts.categories.edit', $category) }}" class="btn btn-primary mr-1">Редактировать</a>
            @if($category->parent_id !== null)
            <form method="POST" action="{{ route('admin.adverts.categories.destroy', $category) }}" class="mr-1">
                @csrf
                @method('DELETE')
                <button class="btn btn-danger">Удалить</button>
            </form>
            @endif
        </div>
        <table class="table table-bordered table-striped">
            <tbody>
            <tr>
                <th>ID</th>
                <td>{{ $category->id }}</td>
            </tr>
            <tr>
                <th>Название</th>
                <td>{{ $category->name }}</td>
            </tr>
            <tr>
                <th>ЧПУ</th>
                <td>{{ $category->slug }}</td>
            </tr>
            @if($category->parent)
                <tr>
                    <th>Родительская категория</th>
                    <td>
                        <a href="{{ route('admin.adverts.categories.show', $category->parent) }}">{{ $category->parent->name }}</a>
                    </td>
                </tr>
            @endif
            </tbody>
        </table>
    </div>

    @if($parentAttributes)
        <div class="card p-3  mb-3">
        <h2>Родительские атрибуты</h2>
        <table class="table table-bordered table-striped">
            <thead>
            <tr>
                <th>ID</th>
                <th>Сортировка</th>
                <th>Имя</th>
                <th>Тип</th>
                <th>Обязательный атрибут</th>
            </tr>
            </thead>
            <tbody>
            @foreach($parentAttributes as $attribute)
                <tr>
                    <td>{{ $attribute->id }}</td>
                    <td>{{ $attribute->sort }}</td>
                    <td>
                        <a href="{{ route('admin.adverts.categories.attributes.show', [$attribute->category, $attribute]) }}">{{ $attribute->name }}</a>
                    </td>
                    <td>{{ $attribute->type }}</td>
                    <td>{!! $attribute->required ? '&#10004;' : '' !!}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
    @endif

    <div class="card p-3  mb-3">
        <h2>Атрибуты</h2>
        <div class="d-flex flex-row mb-3">
            <a href="{{ route('admin.adverts.categories.attributes.create', $category) }}" class="btn btn-primary mr-1">Добавить атрибут для категории</a>
        </div>
        <table class="table table-bordered table-striped">
            <thead>
            <tr>
                <th>ID</th>
                <th>Сортировка</th>
                <th>Имя</th>
                <th>Тип</th>
                <th>Обязательный атрибут</th>
                <th>Изменить</th>
                <th>Удалить</th>

            </tr>
            </thead>
            <tbody>
            @foreach($attributes as $attribute)
                <tr>
                    <td>{{ $attribute->id }}</td>
                    <td>{{ $attribute->sort }}</td>
                    <td><a href="{{ route('admin.adverts.categories.attributes.show', [$category, $attribute]) }}">{{ $attribute->name }}</a></td>
                    <td>{{ $attribute->type }}</td>
                    <td>{!! $attribute->required ? '&#10004;' : '' !!}</td>
                    <td class="text-center">
                        <a href="{{ route('admin.adverts.categories.attributes.edit', [$category, $attribute]) }}" class="btn btn-primary mr-1">Редактировать</a>
                    </td>
                    <td class="text-center">
                        <form method="POST" action="{{ route('admin.adverts.categories.attributes.destroy', [$category, $attribute]) }}" class="mr-1">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-danger">Удалить</button>
                        </form>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>

    <div class="card p-3  mb-3">
        <h2>Дочерние категории</h2>
        <div class="d-flex flex-row mb-3">
            <a href="{{ route('admin.adverts.categories.create-inner', $category) }}" class="btn btn-primary mr-1">Создать дочернюю категорию</a>
        </div>
        @if(count($category->children))
            @include('admin.adverts.categories._list', ['categories' => $category->children])
        @endif
    </div>
@endsection
