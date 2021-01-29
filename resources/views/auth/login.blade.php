@php($pageTitle = 'Вход на сайт')
@extends('layouts.app')
@section('content')
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Login') }} <b class="text-danger">----- Для доступа к админке логин "eaglezzzzz@rambler.ru", пароль "123".-----</b></div>

                <div class="card-body">
                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <div class="form-group row">
                            <label for="email"
                                   class="col-md-4 col-form-label text-md-right">{{ __('E-Mail Address') }}</label>
                            <div class="col-md-6">
                                <input id="email" type="email"
                                       class="form-control @if($errors->has('email')) is-invalid @endif"
                                       name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                                @if($errors->has('email'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="password"
                                   class="col-md-4 col-form-label text-md-right">{{ __('Password') }}</label>

                            <div class="col-md-6">
                                <input id="password" type="password"
                                       class="form-control @if($errors->has('password')) is-invalid @endif"
                                       name="password"
                                       required autocomplete="current-password">
                                @if($errors->has('password'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-6 offset-md-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="remember"
                                           id="remember" {{ old('remember') ? 'checked' : '' }}>

                                    <label class="form-check-label" for="remember">
                                        {{ __('Remember Me') }}
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-8 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Login') }}
                                </button>

                                @if (Route::has('password.request'))
                                    <a class="btn btn-link" href="{{ route('password.request') }}">
                                        {{ __('Forgot Your Password?') }}
                                    </a>
                                @endif
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-4">

            <div class="card mb-2">
                <a href="{{ route('register') }}" class="m-2 btn btn-primary">Зарегистрироваться</a>
            </div>
            <div class="card">
                <div class="card-header">Войти через:</div>
                <div class="card-body">
                    <ul class="list-unstyled">
{{--                        <li><a href="{{ route('login.social-network', ['network' => 'facebook']) }}"><span--}}
{{--                                    class="fa fa-facebook-square"></span> Facebook</a></li>--}}
{{--                        <li><a href="{{ route('login.social-network', ['network' => 'twitter']) }}"><span--}}
{{--                                    class="fa fa-twitter-square"></span> Twitter</a></li>--}}
                        <li><a href="{{ route('login.social-network', ['network' => 'vkontakte']) }}"><span
                                    class="fa fa-vk"></span> В контакте</a></li>
                        <li><a href="{{ route('login.social-network', ['network' => 'github']) }}"><span
                                    class="fa fa-github"></span> GitHub</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    </div>
@endsection
