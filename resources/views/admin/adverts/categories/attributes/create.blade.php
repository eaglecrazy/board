@php($pageTitle = 'Создание атрибута категории')
@extends('layouts.app')

@section('content')
    @include('admin.adverts.categories._nav')
    <form method="POST" action="{{ route('admin.adverts.categories.attributes.store', $category) }}">
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
            <label for="sort" class="col-form-label">Сортировка</label>
            <input id="sort"
                   class="form-control {{ $errors->has('sort') ? 'is-invalid' : '' }}"
                   name="sort"
                   value="{{ old('sort') ? old('sort') : 0}}"
                   required>
            @if($errors->has('sort'))
                <span class="invalid-feedback"><strong>{{ $errors->first('sort') }}</strong></span>
            @endif
        </div>
        <div class="form-group">
            <label for="type" class="col-form-label">Тип атрибута</label>
            <select id="type"
                   class="form-control {{ $errors->has('type') ? 'is-invalid' : '' }}"
                   name="type">
                @foreach($types as $type => $label)
                    <option value="{{ $type }}"{{ $type == old('type') ? ' selected' : '' }}>{{ $label }}</option>
                @endforeach
            </select>
            @if($errors->has('type'))
                <span class="invalid-feedback"><strong>{{ $errors->first('type') }}</strong></span>
            @endif
        </div>

        <div class="form-group">
            <label for="variants" class="col-form-label">Возможные варианты ввода (если нужно вводить значение вручную или атрибут типа "да/нет", то нужно оставить пустое поле).</label>
            <textarea id="variants"
                   class="form-control {{ $errors->has('variants') ? 'is-invalid' : '' }}"
                   name="variants">{{ old('variants') }}</textarea>
            @if($errors->has('variants'))
                <span class="invalid-feedback"><strong>{{ $errors->first('variants') }}</strong></span>
            @endif
        </div>

        <div class="form-group">
            <input type="hidden"
                   name="required"
                   value="0">
            <div class="checkbox">
                <label>
                    <input type="checkbox", name="required" {{ old('required') ? ' checked' : '' }}> Обязательный атрибут.
                </label>
            </div>
            @if($errors->has('required'))
                <span class="invalid-feedback"><strong>{{ $errors->first('required') }}</strong></span>
            @endif
        </div>

        <div class="form-group">
            <button type="submit" class="btn btn-primary">Сохранить</button>
        </div>
    </form>
@endsection
