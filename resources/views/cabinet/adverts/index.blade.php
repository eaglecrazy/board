@extends('layouts.app')

@section('content')
    @include('cabinet._nav', ['page' => 'adverts'])
    <h2>Объявления</h2>
    <a class="nav-link " href="{{ route('cabinet.adverts.create.category') }}">Создать объявление</a>

{{--    эта заготовка для JS --}}
{{--    <div class="region-selector"--}}
{{--         data-selected="{{ json_encode((array)old('regions')) }}"--}}
{{--         data-source="{{ route('ajax.regions') }}">--}}
{{--        <div class="form-group">--}}
{{--            <select class="form-control region-select" data-level="1">--}}
{{--                <option value=""></option>--}}
{{--            </select>--}}
{{--        </div>--}}
{{--    </div>--}}

@endsection
