<div class="card mb-3">
    <div class="card-header h3">Редактирование фотографий</div>
    @if(count($advert->photos))
        <label for="files" class="col-form-label ml-3">В одном объявлении может быть от 1 до 4 фотографий. Максимальный
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
                        @if($advert->photos->count() > 1)
                            <button type="submit" class="btn btn-danger mt-2">Удалить</button>
                        @endif
                    </div>
                </form>
            @endforeach
        </div>
    @endif

    @if($advert->photos->count() < 4)
        <form method="POST"
              action="{{ $editUser === 'admin' ? route('admin.adverts.adverts.update', $advert) : route('cabinet.adverts.photosAdd', $advert) }}"
              enctype="multipart/form-data">
            @csrf
            <input id="files" type="file"
                   class="form-control h-25 py-3 {{ $errors->has('files.*') | $errors->has('files') ? ' is-invalid' : '' }}"
                   name="files[]"
                   multiple required>
            @if ($errors->has('files'))
                <span class="invalid-feedback"><strong>{{ $errors->first('files') }}</strong></span>
            @elseif ($errors->has('files.*'))
                <span class="invalid-feedback"><strong>{{ $errors->first('files.*') }}</strong></span>
            @endif
            <div class="form-group p-4 mb-0">
                <button type="submit" class="btn btn-primary">Добавить фотографии</button>
            </div>
        </form>
    @endif
</div>
