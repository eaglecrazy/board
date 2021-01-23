@php($pageTitle = $advert->title)
@extends('layouts.app')

@section('content')
    @if ($advert->isDraft())
        <div class="alert alert-danger">
            Статус объявления: черновик. Для отображения в поиске объявление должно пройти модерацию. Нажмите кнопку "Опубликовать".
        </div>
        @if ($advert->reject_reason)
            <div class="alert alert-danger">
                Модерация не пройдена. Причина отклонения: {{ $advert->reject_reason }}
            </div>
        @endif
    @elseif($advert->isClosed())
        <div class="alert alert-danger">
            Статус объявления: закрыто.
        </div>
    @elseif($advert->isModeration())
        <div class="alert alert-info">
            Объявление на модерации.
        </div>
    @endif

    @can ('manage-adverts', $advert)
        @include('adverts.show._moderator_panel')
    @endcan

    @can ('manage-own-advert', $advert)
        @include('adverts.show._owner_panel')
    @endcan

    <div class="row">
        <div class="col-md-9">

            <p class="float-right" style="font-size: 36px;">{{ $advert->price }} руб.</p>
            <h1 style="margin-bottom: 10px">{{ $advert->title  }}</h1>
            <p>
                <span class="font-weight-bold">Дата создания:</span> {{ dFormat($advert->created_at) }}. &nbsp;
                @if ($advert->expires_at)
                    <span class="font-weight-bold">Активно до:</span> {{ dFormat($advert->expires_at) }}.
                @endif
            </p>

            @if(!empty($photos))
                <div style="margin-bottom: 20px">
                    <div class="row">
                        <div class="col-10">
                            <div class="main-photo-wrap">
                                <img class="main-photo" src="{{ asset('storage/') . '/' .  $photos[0]}}"/>
                            </div>
                        </div>
                        @if(count($photos) > 1)
                            <div class="col-2">
                                @foreach($photos as $photoLink)
                                    <div class="second-photo-wrap">
                                        <img class="second-photo"
                                             src="{{ asset('storage/') . '/' .  $photoLink}}"
                                             height="100"/>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            @endif

            {{--            e вызывает внутри себя экранирование через html special chars--}}
            {{--            Если вы не хотите экранировать данные, используйте такой синтаксис: {!! !!} --}}
            <h4 class="font-weight-bold">Описание</h4>
            <p>{!! nl2br(e($advert->content)) !!}</p>

            <table class="table table-bordered">
                <tbody>
                @foreach ($advert->category->allAttributes() as $attribute)
                    <tr>
                        <th>{{ $attribute->name }}</th>
                        <td>{{ $advert->getAdvertAttributeValue($attribute) }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>

            @if($advert->address)
                <p><span class="font-weight-bold">Адрес:</span> {{ $advert->address }}</p>

                <div style="margin: 20px 0; border: 1px solid #ddd">
                    <div id="map" style="width: 100%; height: 350px"></div>
                </div>
            @endif

            <p style="margin-bottom: 20px"><span class="font-weight-bold">Продавец:</span> {{ $advert->user->name }}</p>

            <div class="d-flex flex-row mb-4">
                <span class="btn btn-primary phone-button mr-2"
                      data-source="{{ route('adverts.phone', $advert) }}"><span class="fa fa-phone"></span> <span
                        class="number">Показать телефон</span></span>
                @auth
                    @if($user->id !== $advert->user_id)
                        <a href="{{ route('cabinet.dialogs.dialog', $advert) }}" class="btn btn-success mr-2"><span class="fa fa-envelope"></span> Написать сообщение</a>
                    @endif

                    @if ($user && $user->hasInFavorites($advert->id))
                        <form method="POST" action="{{ route('adverts.favorites', $advert) }}" class="mr-2">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-secondary"><span class="fa fa-star"></span> Убрать из избранного
                            </button>
                        </form>
                    @else
                        <form method="POST" action="{{ route('adverts.favorites', $advert) }}" class="mr-2">
                            @csrf
                            <button class="btn btn-danger"><span class="fa fa-star"></span> Добавить в избранное
                            </button>
                        </form>
                    @endif
                @endauth
            </div>

            @if($similar->count())
                <div class="h3">Похожие объявления</div>
                <div class="row">

                    @foreach($similar as $similar_item)
                        <div class="col-sm-6 col-md-4">
                            <div class="card">
                                <img class="card-img-top"
                                     src="{{ asset('storage/') . '/' .  $similarPhotos[$similar_item->id] }}">
                                <div class="card-body">
                                    <div class="card-title h4 mt-0" style="margin: 10px 0">
                                        <a href="{{ route('adverts.show', $similar_item) }}">{{ $similar_item->title }}</a>
                                    </div>
                                    <p class="card-text" style="color: #666"><b>{{ $similar_item->price }} руб.</b></p>
                                    <p class="card-text mt-0"
                                       style="color: #666">{{ Str::limit($similar_item->content, 100) }}</p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
        <div class="col-md-3">
            <div class="banner mb-3 mt-5" data-format="240x400"
                 data-category="{{ $advert->category ? $advert->category->id : '' }}"
                 data-region="{{ $advert->region ? $advert->region->id : '' }}"
                 data-url="{{ route('banner.get') }}"></div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="https://api-maps.yandex.ru/2.1/?lang=ru_RU&amp;apikey={{ env('YANDEX_MAPS_KEY') }}"
            {{--    <script src="https://api-maps.yandex.ru/2.1/?lang=ru_RU&amp;apikey=418aa256-e41b-4893-aa9f-5530867df1a5"--}}
            type="text/javascript"></script>

    <script type='text/javascript'>

        ymaps.ready(init);

        function init() {
            var geocoder = new ymaps.geocode(
                // Строка с адресом, который нужно геокодировать
                '{{ $advert->address }}',
                // 'Абакан, ул.Некрасова, 23',
                // требуемое количество результатов
                {results: 1}
            );
            // После того, как поиск вернул результат, вызывается callback-функция
            geocoder.then(
                function (res) {
                    console.log(res);
                    if (res.metaData.geocoder.found === 0) {
                        // Если местоположение невозможно получить, то просто создаем карту.
                        map = new ymaps.Map('map', {center: [55.751574, 37.573856], zoom: 7});
                        return;
                    }

                    // координаты объекта
                    var coord = res.geoObjects.get(0).geometry.getCoordinates();
                    var map = new ymaps.Map('map', {
                        // Центр карты - координаты первого элемента
                        center: coord,
                        // Коэффициент масштабирования
                        zoom: 12,
                        // включаем масштабирование карты колесом
                        behaviors: ['default', 'scrollZoom'],
                        controls: ['mapTools']
                    });
                    // Добавление метки на карту
                    map.geoObjects.add(res.geoObjects.get(0));
                    // устанавливаем максимально возможный коэффициент масштабирования - 1
                    map.zoomRange.get(coord).then(function (range) {
                        map.setCenter(coord, range[1] - 1)
                    });
                    // Добавление стандартного набора кнопок
                    map.controls.add('mapTools')
                        // Добавление кнопки изменения масштаба
                        .add('zoomControl')
                        // Добавление списка типов карты
                        .add('typeSelector');
                }
            );
        }
    </script>
@endsection
