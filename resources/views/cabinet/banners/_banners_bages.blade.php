@if ($banner->isDraft())
    <span class="badge badge-secondary">Черновик</span>
@elseif ($banner->isOnModeration())
    <span class="badge badge-primary">Модерация</span>
@elseif ($banner->isModerated())
    <span class="badge badge-success">Готов к оплате</span>
@elseif ($banner->isOrdered())
    <span class="badge badge-warning">Ожидает оплаты</span>
@elseif ($banner->isActive())
    <span class="badge badge-primary">Активный</span>
@elseif ($banner->isClosed())
    <span class="badge badge-secondary">Закрыт</span>
@endif
