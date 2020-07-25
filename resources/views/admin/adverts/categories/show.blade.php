@extends('layouts.app')

@section('content')
    @include('admin.adverts.categories._nav')
    <div class="card p-3 mb-3">
        <h2>Category</h2>
        <div class="d-flex flex-row mb-3">
            <a href="{{ route('admin.adverts.categories.edit', $category) }}" class="btn btn-primary mr-1">Edit</a>
            <form method="POST" action="{{ route('admin.adverts.categories.destroy', $category) }}" class="mr-1">
                @csrf
                @method('DELETE')
                <button class="btn btn-danger">Delete</button>
            </form>
        </div>
        <table class="table table-bordered table-striped">
            <tbody>
            <tr>
                <th>ID</th>
                <td>{{ $category->id }}</td>
            </tr>
            <tr>
                <th>Name</th>
                <td>{{ $category->name }}</td>
            </tr>
            <tr>
                <th>Slug</th>
                <td>{{ $category->slug }}</td>
            </tr>
            @if($category->parent)
                <tr>
                    <th>Parent</th>
                    <td>
                        <a href="{{ route('admin.adverts.categories.show', $category->parent) }}">{{ $category->parent->name }}</a>
                    </td>
                </tr>
            @endif
            </tbody>
        </table>
    </div>

    <div class="card p-3  mb-3">
        <h2>Attributes</h2>
        <div class="d-flex flex-row mb-3">
            <a href="{{ route('admin.adverts.categories.attributes.create', $category) }}" class="btn btn-primary mr-1">Create
                atribute</a>
        </div>
        <table class="table table-bordered table-striped">
            <thead>
            <tr>
                <th>Id</th>
                <th>Sort</th>
                <th>Name</th>
                <th>Type</th>
                <th>Required</th>
            </tr>
            </thead>
            <tbody>
            @foreach($attributes as $attribute)
                <tr>
                    <td>{{ $attribute->id }}</td>
                    <td>{{ $attribute->sort }}</td>
                    <td>
                        <a href="{{ route('admin.adverts.categories.attributes.show', [$category, $attribute]) }}">{{ $attribute->name }}</a>
                    </td>
                    <td>{{ $attribute->type }}</td>
                    <td>{{ $attribute->required ? 'Yes' : '' }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>

    <div class="card p-3  mb-3">
        <h2>Inner categories</h2>
        <div class="d-flex flex-row mb-3">
            <a href="{{ route('admin.adverts.categories.create-inner', $category) }}" class="btn btn-primary mr-1">Create
                inner category</a>
        </div>
        @if(count($category->children))
            @include('admin.adverts.categories._list', ['categories' => $category->children])
        @endif
    </div>
@endsection
