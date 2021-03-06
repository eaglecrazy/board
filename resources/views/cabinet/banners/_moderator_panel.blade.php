<div class="card card-default mb-2">
    <div class="card-header h4">Панель модератора</div>
    <div class="card-body d-flex flex-wrap p-1">
        <a href="{{ route('admin.banners.edit', $banner) }}" class="btn btn-primary m-1">Редактировать</a>
        @if ($banner->isOnModeration())
            <form method="POST" action="{{ route('admin.banners.moderate', $banner) }}" class="m-1">
                @csrf
                <button class="btn btn-success">Одобрить</button>
            </form>
            <a href="{{ route('admin.banners.reject', $banner) }}" class="btn btn-warning m-1">Отклонить</a>
        @endif
        @if ($banner->isOrdered())
            <form method="POST" action="{{ route('admin.banners.pay', $banner) }}" class="m-1">
                @csrf
                <button class="btn btn-success">Отметить как оплаченный</button>
            </form>
        @endif
        <form method="POST" action="{{ route('admin.banners.destroy', $banner) }}" class="m-1">
            @csrf
            @method('DELETE')
            <button class="btn btn-danger">Удалить</button>
        </form>
    </div>
</div>
