@php($pageTitle = 'Редактирование региона')
@extends('layouts.app')
@section('content')
    @include('admin.regions._nav')
    <form method="POST" action="{{ route('admin.regions.update', $region) }}">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="name" class="col-form-label">Наименование</label>
            <input id="name" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" name="name"
                   value="{{ old('name', $region->name) }}" required>
            @if ($errors->has('name'))
                <span class="invalid-feedback"><strong>{{ $errors->first('name') }}</strong></span>
            @endif
        </div>
        <div class="form-group">
            <div class="checkbox">
                <label>
                    <input type="checkbox", name="important" {{ old('important') || $region->important ? ' checked' : '' }}> "Важный" регион.
                </label>
            </div>
            @if($errors->has('important'))
                <span class="invalid-feedback"><strong>{{ $errors->first('important') }}</strong></span>
            @endif
        </div>
        <div class="form-group">
            <button type="submit" class="btn btn-primary">Сохранить</button>
        </div>
    </form>
@endsection
