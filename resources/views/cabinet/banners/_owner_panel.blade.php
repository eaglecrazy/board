<div class="card card-default mb-3">
    <div class="card-header h4">Управление баннером</div>
    <div class="card-body d-flex flex-row mv0">
        @if ($banner->canBeChanged())
            <a href="{{ route('cabinet.banners.edit', $banner) }}" class="btn btn-primary mr-1">Редактировать</a>
            <a href="{{ route('cabinet.banners.edit_file', $banner) }}" class="btn btn-primary mr-1">Изменить
                файл</a>
        @endif
        @if ($banner->isDraft())
            <form method="POST" action="{{ route('cabinet.banners.sendToModeration', $banner) }}" class="mr-1">
                @csrf
                <button class="btn btn-success">Отправить на модерацию</button>
            </form>
        @endif
        @if ($banner->isOnModeration())
            <form method="POST" action="{{ route('cabinet.banners.cancelModeration', $banner) }}" class="mr-1">
                @csrf
                <button class="btn btn-secondary">Отозвать с модерации</button>
            </form>
        @endif
        @if ($banner->isModerated())
            <form method="POST" action="{{ route('cabinet.banners.order', $banner) }}" class="mr-1">
                @csrf
                <button class="btn btn-success">Оплатить</button>
            </form>
        @endif
        @if ($banner->canBeRemoved())
            <form method="POST" action="{{ route('cabinet.banners.destroy', $banner) }}" class="mr-1">
                @csrf
                @method('DELETE')
                <button class="btn btn-danger">Удалить</button>
            </form>
        @endif
    </div>
</div>
