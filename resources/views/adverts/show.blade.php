@extends('layouts.app')

@section('content')
    @if ($advert->isDraft())
        <div class="alert alert-danger">
            Advert status: draft
        </div>
        @if ($advert->reject_reason)
            <div class="alert alert-danger">
                Reject reason: {{ $advert->reject_reason }}
            </div>
        @endif
    @elseif($advert->isClosed())
        <div class="alert alert-danger">
            Advert status: closed
        </div>
    @endif

    @can ('manage-adverts', $advert)
        <h3>Moderation access</h3>
        <div class="d-flex flex-row mb-3">
            <a href="{{ route('admin.adverts.adverts.edit', $advert) }}" class="btn btn-primary mr-1">Edit</a>
            <a href="{{ route('admin.adverts.adverts.photos', $advert) }}" class="btn btn-primary mr-1">Photos</a>
            @if ($advert->isModeration())
                <form method="POST" action="{{ route('admin.adverts.adverts.moderate', $advert) }}" class="mr-1">
                    @csrf
                    <button class="btn btn-success">Moderate</button>
                </form>
            @endif

            @if ($advert->isModeration() || $advert->isActive())
                <a href="{{ route('admin.adverts.adverts.reject', $advert) }}" class="btn btn-danger mr-1">Reject</a>
            @endif

            <form method="POST" action="{{ route('admin.adverts.adverts.destroy', $advert) }}" class="mr-1">
                @csrf
                @method('DELETE')
                <button class="btn btn-danger">Delete</button>
            </form>
        </div>
    @endcan

    @can ('manage-own-advert', $advert)
        <h3>Advert owner access</h3>
        <div class="d-flex flex-row mb-3">
            <a href="{{ route('cabinet.adverts.edit', $advert) }}" class="btn btn-primary mr-1">Edit</a>
            <a href="{{ route('cabinet.adverts.photos', $advert) }}" class="btn btn-primary mr-1">Photos</a>

            @if ($advert->isDraft() || $advert->isClosed())
                <form method="POST" action="{{ route('cabinet.adverts.send', $advert) }}" class="mr-1">
                    @csrf
                    <button class="btn btn-success">Publish</button>
                </form>
            @endif
            @if ($advert->isActive())
                <form method="POST" action="{{ route('cabinet.adverts.close', $advert) }}" class="mr-1">
                    @csrf
                    <button class="btn btn-success">Close</button>
                </form>
            @endif

            <form method="POST" action="{{ route('cabinet.adverts.destroy', $advert) }}" class="mr-1">
                @csrf
                @method('DELETE')
                <button class="btn btn-danger">Delete</button>
            </form>
        </div>
    @endcan

    <div class="row">
        <div class="col-md-9">

            <p class="float-right" style="font-size: 36px;">{{ $advert->price }}</p>
            <h1 style="margin-bottom: 10px">{{ $advert->title  }}</h1>
            <p>
                Created date: {{ $advert->created_at }} &nbsp;
                @if ($advert->expires_at)
                    Expires: {{ $advert->expires_at }}
                @endif
            </p>

            {{--        Блок для фоток          --}}
            <div style="margin-bottom: 20px">
                <div class="row">
                    <div class="col-10">
                        <div style="height: 400px; background: #f6f6f6; border: 1px solid #ddd"></div>
                    </div>
                    <div class="col-2">
                        <div style="height: 100px; background: #f6f6f6; border: 1px solid #ddd"></div>
                        <div style="height: 100px; background: #f6f6f6; border: 1px solid #ddd"></div>
                        <div style="height: 100px; background: #f6f6f6; border: 1px solid #ddd"></div>
                        <div style="height: 100px; background: #f6f6f6; border: 1px solid #ddd"></div>
                    </div>
                </div>
            </div>

            {{--            e вызывает внутри себя экранирование через html special chars--}}
            {{--            Если вы не хотите экранировать данные, используйте такой синтаксис: {!! !!} --}}
            <p>{!! nl2br(e($advert->content)) !!}</p>

            <table class="table table-bordered">
                <tbody>
                @foreach ($advert->category->allAttributes() as $attribute)
                    <tr>
                        <th>{{ $attribute->name }}</th>
                        <td>{{ $advert->getValue($attribute->id) }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>

            <p>Address: {{ $advert->address }}</p>

            <div style="margin: 20px 0; border: 1px solid #ddd">
                <div id="map" style="width: 100%; height: 350px"></div>
            </div>

            <p style="margin-bottom: 20px">Seller: {{ $advert->user->name }}</p>

            <div class="d-flex flex-row mb-3">
                <span class="btn btn-success mr-1"><span class="fa fa-envelope"></span> Send Message</span>
                <span class="btn btn-primary phone-button mr-1"
                      data-source="{{ route('adverts.phone', $advert) }}"><span class="fa fa-phone"></span> <span
                        class="number">Show Phone Number</span></span>
                {{--                            @if ($user && $user->hasInFavorites($advert->id))--}}
                {{--                                <form method="POST" action="{{ route('adverts.favorites', $advert) }}" class="mr-1">--}}
                {{--                                    @csrf--}}
                {{--                                    @method('DELETE')--}}
                {{--                                    <button class="btn btn-secondary"><span class="fa fa-star"></span> Remove from Favorites</button>--}}
                {{--                                </form>--}}
                {{--                            @else--}}
                {{--                                <form method="POST" action="{{ route('adverts.favorites', $advert) }}" class="mr-1">--}}
                {{--                                    @csrf--}}
                {{--                                    <button class="btn btn-danger"><span class="fa fa-star"></span> Add to Favorites</button>--}}
                {{--                                </form>--}}
                {{--                            @endif--}}
            </div>

            {{--            <hr/>--}}

            @if($similar->count())
                <div class="h3">Similar adverts</div>
                <div class="row">

                    @foreach($similar as $similar_item)
                        <div class="col-sm-6 col-md-4">
                            <div class="card">
                                <img class="card-img-top"
                                     src="https://images.pexels.com/photos/297933/pexels-photo-297933.jpeg?w=1260&h=750&auto=compress&cs=tinysrgb"
                                     alt=""/>
                                <div class="card-body">
                                    <div class="card-title h4 mt-0" style="margin: 10px 0"><a
                                            href="#">{{ $similar_item->title }}</a></div>
                                    <p class="card-text" style="color: #666"><b>{{ $similar_item->price }} руб.</b></p>
                                    <p class="card-text"
                                       style="color: #666">{{ Str::limit($similar_item->content, 100) }}</p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
        <div class="col-md-3">
            <div
                style="height: 400px; background: #f6f6f6; border: 1px solid #ddd; margin-bottom: 20px">баннер 1
            </div>
            <div
                style="height: 400px; background: #f6f6f6; border: 1px solid #ddd; margin-bottom: 20px">баннер 2
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="https://api-maps.yandex.ru/2.1/?lang=ru_RU&amp;apikey=418aa256-e41b-4893-aa9f-5530867df1a5"
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
                        zoom: 13,
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
