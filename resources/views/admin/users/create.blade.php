@php($pageTitle = 'Создание пользователя')
@extends('layouts.app')

@section('content')
    @include('admin.users._nav')
    <form method="POST" action="{{ route('admin.users.store') }}">
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
            <label for="email" class="col-form-label">Email</label>
            <input id="email"
                   type="email"
                   class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}"
                   name="email"
                   value="{{ old('email') }}"
                   required>
            @if($errors->has('email'))
                <span class="invalid-feedback"><strong>{{ $errors->first('email') }}</strong></span>
            @endif
        </div>
        <div class="form-group">
            <label for="password" class="col-form-label">Пароль</label>
            <input id="password"
                   class="form-control {{ $errors->has('password') ? 'is-invalid' : '' }}"
                   name="password"
                   required
                   type="password">
            @if($errors->has('password'))
                <span class="invalid-feedback"><strong>{{ $errors->first('password') }}</strong></span>
            @endif
        </div>
        <div class="form-group">
            <label for="password_confirmation" class="col-form-label">Подтверждение пароля</label>
            <input id="password_confirmation"
                   class="form-control {{ $errors->has('password_confirmation') ? 'is-invalid' : '' }}"
                   name="password_confirmation"
                   required
                   type="password">
            @if($errors->has('password_confirmation'))
                <span class="invalid-feedback"><strong>{{ $errors->first('password_confirmation') }}</strong></span>
            @endif
        </div>
        <div class="form-group">
            <button type="submit" class="btn btn-primary">Сохранить</button>
        </div>
    </form>
@endsection
