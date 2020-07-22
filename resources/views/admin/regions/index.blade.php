@extends('layouts.app')

@section('content')
    @include('admin.regions._nav')

    <div class="d-flex flex-row mb-3">
        <a href="{{ route('admin.regions.create') }}" class="btn btn-primary mr-1">Create</a>
    </div>

    <table class="table table-bordered table-striped">
        <thead>
        <tr>
            <th>Id</th>
            <th>Name</th>
            <th>Slug</th>
        </tr>
        </thead>
        <tbody>
        @foreach($regions as $region)
            <tr>
                <td>{{ $region->id }}</td>
                <td><a href="{{ route('admin.regions.show', $region) }}">{{ $region->name }}</a></td>
                <td>{{ $region->slug }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endsection
