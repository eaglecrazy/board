@extends('layouts.app')
@section('content')
    @include('admin.adverts.categories._nav')
    <form method="POST" action="{{ route('admin.adverts.categories.update', $current) }}">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="name" class="col-form-label">Name</label>
            <input id="name" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" name="name"
                   value="{{ old('name', $current->name) }}" required>
            @if ($errors->has('name'))
                <span class="invalid-feedback"><strong>{{ $errors->first('name') }}</strong></span>
            @endif
        </div>

        <div class="form-group">
            <label for="parent" class="col-form-label">Parent</label>
            <select id="parent"
                    class="form-control {{ $errors->has('parent') ? 'is-invalid' : '' }}"
                    name="parent">
                <option value=""></option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}"{{ $category->id == old('category') || ($current->parent && $category->id === $current->parent->id) ? ' selected' : ''  }}>
                        @for ($i = 0; $i < $category->depth; $i++) &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; @endfor {{ $category->name }}
                    </option>
                @endforeach
            </select>
            @if($errors->has('category'))
                <span class="invalid-feedback"><strong>{{ $errors->first('category') }}</strong></span>
            @endif
        </div>

        <div class="form-group">
            <button type="submit" class="btn btn-primary">Save</button>
        </div>
    </form>
@endsection
