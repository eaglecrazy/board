<ul class="nav nav-tabs mb-3">
    @can('manage-adverts')
        <li class="nav-item"><a href="{{ route('admin.adverts.adverts.index') }}"
                                class="nav-link{{ $page === 'adverts' ? ' active' : '' }}">Adverts</a></li>
    @endcan
    @can('manage-users')
        <li class="nav-item"><a href="{{ route('admin.users.index') }}"
                                class="nav-link{{ $page === 'users' ? ' active' : '' }}">Users</a></li>
    @endcan

    @can('manage-regions')
        <li class="nav-item"><a href="{{ route('admin.regions.index') }}"
                                class="nav-link{{ $page === 'regions' ? ' active' : '' }}">Regions</a></li>
    @endcan

    @can('manage-adverts-categories')
        <li class="nav-item"><a href="{{ route('admin.adverts.categories.index') }}"
                                class="nav-link{{ $page === 'categories' ? ' active' : '' }}">Categories</a></li>
    @endcan
</ul>
