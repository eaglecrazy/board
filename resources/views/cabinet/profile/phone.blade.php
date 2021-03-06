@php($pageTitle = 'Подтверждение телефона')
@extends('layouts.app')

@section('content')
    @include('cabinet._nav', ['page' => 'profile'])

{{--    @dump(Auth::user()->phone_verify_token)--}}

    <form method="POST" action="{{ route('cabinet.profile.phone.verify') }}">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="token" class="col-form-label">SMS код <br><b style="color: red">Если код не пришёл то введите: {{ Auth::user()->phone_verify_token }}</b></label>
            <input id="token" class="form-control{{ $errors->has('token') ? ' is-invalid' : '' }}" name="token" value="{{ old('token') }}" required>
            @if ($errors->has('token'))
                <span class="invalid-feedback"><strong>{{ $errors->first('token') }}</strong></span>
            @endif
        </div>

        <div class="form-group">
            <button type="submit" class="btn btn-primary">Подтвердить телефон</button>
        </div>
    </form>
@endsection
