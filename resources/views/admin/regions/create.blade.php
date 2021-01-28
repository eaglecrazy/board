@php($pageTitle = 'Создание региона')
@extends('layouts.app')

@section('content')
    @include('admin.regions._nav')
    @if($parent)
        <h2>Создать внутренний регион для: {{ $parent->name }}</h2>
    @else
        <h2>Создать корневой регион</h2>
    @endif
    <form method="POST" action="{{ route('admin.regions.store', ['parent' => $parent ? $parent->id : null]) }}">
        @csrf

        <div class="form-group">
            <label for="name" class="col-form-label">Наименование региона</label>
            <input id="name"
                   class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}"
                   name="name"
                   value="{{ old('name') }}"
                   required>
            @if($errors->has('name'))
                <span class="invalid-feedback"><strong>{{ $errors->first('name') }}</strong></span>
            @endif
        </div>

        <div class="form-group">
            <div class="checkbox">
                <label>
                    <input type="checkbox", name="important" {{ old('important') ? ' checked' : '' }}> "Важный" регион.
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
