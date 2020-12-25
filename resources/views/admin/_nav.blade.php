<ul class="nav nav-tabs mb-3">
    @can('manage-adverts')
        <li class="nav-item"><a href="{{ route('admin.adverts.adverts.index') }}"
                                class="nav-link{{ $page === 'adverts' ? ' active' : '' }}">Объявления</a></li>
    @endcan

    @can('manage-banners')
        <li class="nav-item"><a href="{{ route('admin.banners.index') }}"
                                class="nav-link{{ $page === 'banners' ? ' active' : '' }}">Баннеры</a></li>
    @endcan

    @can('manage-users')
        <li class="nav-item"><a href="{{ route('admin.users.index') }}"
                                class="nav-link{{ $page === 'users' ? ' active' : '' }}">Пользователи</a></li>
    @endcan

    @can('manage-regions')
        <li class="nav-item"><a href="{{ route('admin.regions.index') }}"
                                class="nav-link{{ $page === 'regions' ? ' active' : '' }}">Регионы</a></li>
    @endcan

    @can('manage-adverts-categories')
        <li class="nav-item"><a href="{{ route('admin.adverts.categories.index') }}"
                                class="nav-link{{ $page === 'categories' ? ' active' : '' }}">Категории</a></li>
    @endcan

    @can('manage-pages')
        <li class="nav-item"><a href="{{ route('admin.pages.index') }}"
                                class="nav-link{{ $page === 'pages' ? ' active' : '' }}">Страницы</a></li>
    @endcan

    @can('manage-tickets')
        <li class="nav-item"><a href="{{ route('admin.tickets.index') }}"
                                class="nav-link{{ $page === 'pages' ? ' active' : '' }}">Заявки</a></li>
    @endcan
</ul>
