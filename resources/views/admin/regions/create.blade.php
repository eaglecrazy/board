@extends('layouts.app')

@section('content')
    @include('admin.regions._nav')
    <form method="POST" action="{{ route('admin.regions.store') }}">
        @csrf
        <div class="form-group">
            <label for="name" class="col-form-label">Name</label>
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
                <span class="invalid-feedback"><strong>{{ $errors->first('name') }}</strong></span>
            @endif
        </div>
        <div class="form-group">
            <label for="password" class="col-form-label">Password</label>
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
            <label for="password_confirmation" class="col-form-label">Password confirmation</label>
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
            <button type="submit" class="btn btn-primary">Save</button>
        </div>
    </form>
@endsection