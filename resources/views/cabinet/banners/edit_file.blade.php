@extends('layouts.app')

@section('content')
    @include('cabinet._nav', ['page' => 'banners'])

    <form method="POST" action="{{ route('cabinet.banners.edit_file', $banner) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="format" class="col-form-label">Формат</label>
            <select id="format" class="form-control{{ $errors->has('format') ? ' is-invalid' : '' }}" name="format">
                @foreach ($formats as $value)
                    <option
                        value="{{ $value }}"{{ $value === old('format', $banner->format) ? ' selected' : '' }}>{{ $value }}</option>
                @endforeach;
            </select>
            @if ($errors->has('format'))
                <span class="invalid-feedback"><strong>{{ $errors->first('format') }}</strong></span>
            @endif
        </div>

        <div class="form-group">
            <label for="file" class="col-form-label">Баннер</label>
            <input id="file" type="file" class="form-control h-25{{ $errors->has('file') ? ' is-invalid' : '' }}" name="file"
                   required>
            @if ($errors->has('file'))
                <span class="invalid-feedback"><strong>{{ $errors->first('file') }}</strong></span>
            @endif
        </div>

        <div class="form-group">
            <button type="submit" class="btn btn-primary">Сохранить</button>
        </div>
    </form>

@endsection
