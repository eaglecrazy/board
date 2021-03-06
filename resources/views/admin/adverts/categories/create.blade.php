@php($pageTitle = 'Создание категории')
@extends('layouts.app')

@section('content')
    @include('admin.adverts.categories._nav')
    <form method="POST" action="{{ route('admin.adverts.categories.store') }}">
        @csrf

        <div class="form-group">
            <label for="name" class="col-form-label">Имя</label>
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
            <label for="parent" class="col-form-label">Родительская категория</label>
            <select id="parent"
                   class="form-control {{ $errors->has('parent') ? 'is-invalid' : '' }}"
                   name="parent">
                <option value=""></option>
                @foreach($parents as $parent)
                    <option value="{{ $parent->id }}"{{ ((isset($current) && $parent->id == $current->id) || $parent->id == old('parent')) ? ' selected' : ''  }}>
                        @for ($i = 0; $i < $parent->depth; $i++) &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; @endfor {{ $parent->name }}
                    </option>
                @endforeach
            </select>
            @if($errors->has('parent'))
                <span class="invalid-feedback"><strong>{{ $errors->first('parent') }}</strong></span>
            @endif
        </div>

        <div class="form-group">
            <button type="submit" class="btn btn-primary">Сохранить</button>
        </div>
    </form>
@endsection
