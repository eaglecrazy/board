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
            <th>Manage</th>
        </tr>
        </thead>
        <tbody>
        @foreach($categories as $category)
            <tr>
                <td>
                    @for($i = 0; $i < $category->depth; $i++) &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; @endfor
                    <a href="{{ route('admin.adverts.categories.show', $category) }}">{{ $category->name }}</a></td>
                <td>{{ $category->slug }}</td>
                <td>
                    <div class="d-flex flex-row justify-content-center">
                        <form
                            class="mr-2"
                            method="POST"
                            action="{{ route('admin.adverts.categories.first', $category) }}">
                            @csrf
                            <button class="btn btn-sm btn-outline-primary"><span class="fa fa-angle-double-up"></span></button>
                        </form>
                        <form
                            class="mr-2"
                            method="POST"
                            action="{{ route('admin.adverts.categories.up', $category) }}">
                            @csrf
                            <button class="btn btn-sm btn-outline-primary"><span class="fa fa-angle-up"></span></button>
                        </form>
                        <form
                            class="mr-2"
                            method="POST"
                            action="{{ route('admin.adverts.categories.down', $category) }}">
                            @csrf
                            <button class="btn btn-sm btn-outline-primary"><span class="fa fa-angle-down"></span></button>
                        </form>
                        <form
                            class="mr-2"
                            method="POST"
                            action="{{ route('admin.adverts.categories.last', $category) }}">
                            @csrf
                            <button class="btn btn-sm btn-outline-primary"><span class="fa fa-angle-double-down"></span></button>
                        </form>

                    </div>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endsection
