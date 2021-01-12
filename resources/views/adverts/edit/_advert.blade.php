<form method="POST" action="{{ $editUser === 'admin' ? route('admin.adverts.adverts.update', $advert) : route('cabinet.adverts.advertUpdate', $advert) }}">
    @csrf
    @method('PUT')
    <div class="card mb-3">
        <div class="card-header h3">Редактирование объявления</div>
        <div class="card-body pb-2">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="title" class="col-form-label">Наименование</label>
                        <input id="title" class="form-control{{ $errors->has('title') ? ' is-invalid' : '' }}"
                               name="title" value="{{ old('title') ?? $advert->title }}" required>
                        @if ($errors->has('title'))
                            <span class="invalid-feedback"><strong>{{ $errors->first('title') }}</strong></span>
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
                               value="{{ old('price') ?? $advert->price }}" required>
                        @if ($errors->has('price'))
                            <span class="invalid-feedback"><strong>{{ $errors->first('price') }}</strong></span>
                        @endif
                    </div>
                </div>
            </div>

            @if($advert->region)
                <div class="form-group">
                    <label for="address" class="col-form-label">Адрес</label>
                    <div class="row">
                        <div class="col-md-12">
                            <input id="address" type="text"
                                   class="form-control{{ $errors->has('address') ? ' is-invalid' : '' }}"
                                   name="address"
                                   value="{{ old('address') ?? $advert->address }}">
                            @if ($errors->has('address'))
                                <span
                                    class="invalid-feedback"><strong>{{ $errors->first('address') }}</strong></span>
                            @endif
                        </div>
                    </div>
                </div>
            @endif

            <div class="form-group">
                <label for="content" class="col-form-label">Подробное описание</label>
                <textarea id="content" class="form-control{{ $errors->has('content') ? ' is-invalid' : '' }}"
                          name="content" rows="10" required>{{ old('content') ?? $advert->content }}</textarea>
                @if ($errors->has('content'))
                    <span class="invalid-feedback"><strong>{{ $errors->first('content') }}</strong></span>
                @endif
            </div>
        </div>
        <div class="form-group ml-4">
            <button type="submit" class="btn btn-primary">Сохранить</button>
        </div>
    </div>
</form>
