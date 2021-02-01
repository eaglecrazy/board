@php($pageTitle = 'Создание объявления')
@extends('layouts.app')

@section('content')
    <form method="POST" action="{{ route('cabinet.adverts.create.advert.store', [$category, $region]) }}"
          enctype="multipart/form-data">
        @csrf
        <div class="card mb-3">
            <div class="card-header h3">Описание объявления</div>
            <div class="card-body pb-2">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="title" class="col-form-label">Название</label>
                            <input id="title" class="form-control{{ $errors->has('title') ? ' is-invalid' : '' }}"
                                   name="title" value="{{ old('title') }}" required>
                            @if ($errors->has('title'))
                                <span class="invalid-feedback">><strong>{{ $errors->first('title') }}</strong></span>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="price" class="col-form-label">Стоимость</label>
                            <input id="price" type="number"
                                   min="1"
                                   max="2147483646"
                                   class="form-control{{ $errors->has('price') ? ' is-invalid' : '' }}" name="price"
                                   value="{{ old('price') }}" required>
                            @if ($errors->has('price'))
                                <span class="invalid-feedback">><strong>{{ $errors->first('price') }}</strong></span>
                            @endif
                        </div>
                    </div>
                </div>

                @if($region)
                    <div class="form-group">
                        <label for="address" class="col-form-label">Адрес</label>
                        <div class="row">
                            <div class="col-md-11  my-2">
                                <input id="address" type="text"
                                       class="form-control{{ $errors->has('address') ? ' is-invalid' : '' }}"
                                       name="address"
                                       value="{{ old('address', $region->getAddress()) }}">
                                @if ($errors->has('address'))
                                    <span
                                        class="invalid-feedback">><strong>{{ $errors->first('address') }}</strong></span>
                                @endif
                            </div>
                            {{--                             Кнопка определения местоположения, работает только если есть https --}}
                            <div class="col-md-1 my-2">
                                <span class="btn btn-primary btn-block location-button"
                                      data-target="#address"><span
                                        class="fa fa-location-arrow"></span></span>
                            </div>
                        </div>
                    </div>
                @endif

                <div class="form-group">
                    <label for="content" class="col-form-label">Подробное описание</label>
                    <textarea id="content" class="form-control{{ $errors->has('content') ? ' is-invalid' : '' }}"
                              name="content" rows="10" required>{{ old('content') }}</textarea>
                    @if ($errors->has('content'))
                        <span class="invalid-feedback">><strong>{{ $errors->first('content') }}</strong></span>
                    @endif
                </div>
            </div>
        </div>

        <div class="card mb-3">
            <div class="card-header h3">Характеристики</div>
            <div class="card-body pb-2">
                @include('cabinet.adverts.create._attributes')
            </div>
        </div>

        <div class="card mb-3">
            <div class="card-header h3">Фотографии</div>
            <label for="files" class="col-form-label ml-3">Нужно загрузить от 1 до 4 фотографий. Максимальный размер
                1Мб.</label>
            <input id="files" type="file"
                   class="form-control h-25 py-3 {{ $errors->has('files.*') | $errors->has('files') ? ' is-invalid' : '' }}"
                   name="files[]"
                   multiple required>
            @if ($errors->has('files'))
                <span class="invalid-feedback">><strong>{{ $errors->first('files') }}</strong></span>
            @elseif ($errors->has('files.*'))
                <span class="invalid-feedback">><strong>{{ $errors->first('files.*') }}</strong></span>
            @endif

        </div>

        <div class="form-group">
            <button type="submit" class="btn btn-primary">Сохранить</button>
        </div>
    </form>
@endsection
