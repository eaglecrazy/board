@extends('layouts.app')

@section('content')
    @include('admin.adverts.categories._nav')
    <div class="d-flex flex-row mb-3">
        <a href="{{ route('admin.adverts.categories.create') }}" class="btn btn-success mr-1">Add category</a>
    </div>

    <table class="table table-bordered table-striped">
        <thead>
        <tr>
            <th>Name</th>
            <th>Slug</th>
        </tr>
        </thead>
        <tbody>
        @foreach($categories as $category)
            <tr>
                <td>
                    @for($i = 0; $i < $category->depth; $i++) &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; @endfor
                    <a href="{{ route('admin.adverts.categories.show', $category) }}">{{ $category->name }}</a></td>
                <td>{{ $category->slug }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endsection
