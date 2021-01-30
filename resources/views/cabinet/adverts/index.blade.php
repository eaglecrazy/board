@php($pageTitle = 'Мои объявления')
@extends('layouts.app')
@section('content')
    @include('cabinet._nav', ['page' => 'adverts'])
    <h2>Мои объявления</h2>
    <div class="card mb-3">
        <div class="card-header">Фильтрация</div>
        <div class="card-body">
            <form action="?" method="GET">
                <div class="row">
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label for="title" class="col-form-label">Название</label>
                            <input id="title" class="form-control" name="title" minlength="3" maxlength="255" value="{{ request('title') }}">
                        </div>
                    </div>
                    <div class="col-sm-2">
                        <div class="form-group">
                            <label for="region" class="col-form-label">Регион</label>
                            <input id="region" class="form-control" name="region" minlength="3" maxlength="255" value="{{ request('region') }}">
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label for="category" class="col-form-label">Категория</label>
                            <input id="category" class="form-control" name="category" minlength="3" maxlength="255"  value="{{ request('category') }}">
                        </div>
                    </div>
                    <div class="col-sm-2">
                        <div class="form-group">
                            <label for="status" class="col-form-label">Статус</label>
                            <select id="status" class="form-control" name="status">
                                <option value=""></option>
                                @foreach ($statuses as $value => $label)
                                    <option value="{{ $value }}"{{ $value === request('status') ? ' selected' : '' }}>{{ $label }}</option>
                                @endforeach;
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-2">
                        <div class="form-group">
                            <label class="col-form-label">&nbsp;</label><br/>
                            <button type="submit" class="btn btn-primary">Искать</button>
                            <a href="?" class="btn btn-outline-secondary">Сброс</a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="table-responsive">
        <table class="table table-striped">
        <thead>
        <tr>
            <th>Название</th>
            <th>Регион</th>
            <th>Категория</th>
            <th>Статус</th>
        </tr>
        </thead>
        <tbody>

        @foreach ($adverts as $advert)
            <tr>
                <td><a href="{{ route('adverts.show', $advert) }}" target="_blank">{{ $advert->title }}</a></td>
                <td>
                    @if ($advert->region)
                        {{ $advert->region->name }}
                    @endif
                </td>
                <td>{{ $advert->category->name }}</td>
                <td>
                    @if ($advert->isDraft())
                        <span class="badge badge-secondary">Черновик</span>
                    @elseif ($advert->isModeration())
                        <span class="badge badge-primary">На модерации</span>
                    @elseif ($advert->isActive())
                        <span class="badge badge-primary">Активно</span>
                    @elseif ($advert->isClosed())
                        <span class="badge badge-secondary">Закрыто</span>
                    @endif
                </td>
            </tr>
        @endforeach

        </tbody>
    </table>
    </div>
    {{ $adverts->links() }}

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
