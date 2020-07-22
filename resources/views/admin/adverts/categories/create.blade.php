@extends('layouts.app')

@section('content')
    @include('admin.adverts.categories._nav')
    @if($parent)
        <h2>Creating an inland category for {{ $parent->name }}</h2>
    @else
        <h2>Creating an root category.</h2>
    @endif
    <form method="POST" action="{{ route('admin.adverts.categories.store', ['parent' => $parent ? $parent->id : null]) }}">
        @csrf

        <div class="form-group">
            <label for="name" class="col-form-label">category name</label>
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
            <button type="submit" class="btn btn-primary">Save</button>
        </div>
    </form>
@endsection
