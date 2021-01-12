<div class="card card-default mb-2">
    <div class="card-header h4">Панель модератора</div>
    <div class="card-body d-flex flex-row mv0">
        <a href="{{ route('admin.adverts.adverts.edit', $advert) }}"
           class="btn btn-primary mr-2">Редактировать</a>
        @if ($advert->isModeration())
            <form method="POST" action="{{ route('admin.adverts.adverts.moderate', $advert) }}"
                  class="mr-2">
                @csrf
                <button class="btn btn-success">Одобрить публикацию</button>
            </form>
        @endif

        @if ($advert->isModeration() || $advert->isActive())
            <a href="{{ route('admin.adverts.adverts.reject', $advert) }}"
               class="btn btn-danger mr-2">Отклонить</a>
        @endif

        <form method="POST" action="{{ route('admin.adverts.adverts.destroy', $advert) }}" class="mr-2">
            @csrf
            @method('DELETE')
            <button class="btn btn-danger">Удалить</button>
        </form>
    </div>
</div>
