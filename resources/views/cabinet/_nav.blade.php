<ul class="nav nav-tabs mb-3">
{{--    <li class="nav-item"><a class="nav-link{{ $page === 'home' ? ' active' : '' }}" href="{{ route('cabinet.home') }}">Кабинет</a></li>--}}
    <li class="nav-item"><a class="nav-link{{ $page === 'adverts' ? ' active' : '' }}" href="{{ route('cabinet.adverts.index') }}">Мои объявления</a></li>
    <li class="nav-item"><a class="nav-link{{ $page === 'dialogs' ? ' active' : '' }}" href="{{ route('cabinet.dialogs.index') }}">Сообщения</a></li>
    <li class="nav-item"><a class="nav-link{{ $page === 'favorites' ? ' active' : '' }}" href="{{ route('cabinet.favorites.index') }}">Избранное</a></li>
    <li class="nav-item"><a class="nav-link{{ $page === 'banners' ? ' active' : '' }}" href="{{ route('cabinet.banners.index') }}">Баннеры</a></li>
    <li class="nav-item"><a class="nav-link{{ $page === 'profile' ? ' active' : '' }}" href="{{ route('cabinet.profile.home') }}">Профиль</a></li>
    <li class="nav-item"><a class="nav-link{{ $page === 'tickets' ? ' active' : '' }}" href="{{ route('cabinet.tickets.index') }}">Техническая поддержка</a></li>
</ul>
