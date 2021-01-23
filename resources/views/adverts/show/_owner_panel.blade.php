<div class="card card-default mb-3">
    <div class="card-header h4">Управление вашим объявлением</div>
    <div class="card-body d-flex flex-row mv0">
        <a href="{{ route('cabinet.adverts.edit', $advert) }}"
           class="btn btn-primary mr-2">Редактировать</a>
        @if ($advert->isDraft() || $advert->isClosed())
            <form method="POST" action="{{ route('cabinet.adverts.sendToModeration', $advert) }}" class="mr-2">
                @csrf
                <button class="btn btn-success">Опубликовать</button>
            </form>
        @endif
        @if ($advert->isActive())
            <form method="POST" action="{{ route('cabinet.adverts.close', $advert) }}" class="mr-2">
                @csrf
                <button class="btn btn-success">Закрыть</button>
            </form>
        @endif

        <form method="POST" action="{{ route('cabinet.adverts.destroy', $advert) }}" class="mr-2">
            @csrf
            @method('DELETE')
            <button class="btn btn-danger">Удалить</button>
        </form>
    </div>
</div>
