<div class="card mb-3">
    <div class="card-header h3">Редактирование фотографий</div>
    @if(count($advert->photos))
        <label for="files" class="col-form-label ml-3">В одном объявлении может быть не более 4 фотографий. Максимальный
            размер фото 1Мб.</label>
        <div class="d-flex p-3">
            @foreach($advert->photos as $photo)
                <form method="POST" action="{{ route('cabinet.adverts.destroyPhoto', [$advert, $photo]) }}"
                      class="mr-2">
                    @csrf
                    @method('DELETE')
                    <div class="second-photo-wrap d-flex flex-column mr-3 p-2 border border-secondary">
                        <img class=""
                             src="{{ asset('storage/') . '/' .  $photo->file }}"
                             height="100"/>
                        <button type="submit" class="btn btn-danger mt-2">Удалить</button>
                    </div>
                </form>
            @endforeach
        </div>
    @endif
    <form method="POST"
          action="{{ Auth::user()->can('manage-adverts') ? route('admin.adverts.adverts.update', $advert) : route('cabinet.adverts.update', $advert) }}">
        @csrf
        <input id="files" type="file"
               class="form-control h-25 py-3 {{ $errors->has('files.*') | $errors->has('files') ? ' is-invalid' : '' }}"
               name="files[]"
               multiple>
        @if ($errors->has('files'))
            <span class="invalid-feedback"><strong>{{ $errors->first('files') }}</strong></span>
        @elseif ($errors->has('files.*'))
            <span class="invalid-feedback"><strong>{{ $errors->first('files.*') }}</strong></span>
        @endif
    </form>
</div>
