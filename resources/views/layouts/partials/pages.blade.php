<ul class="navbar-nav mr-auto">
    @foreach (array_slice($menuPages, 0, 3) as $page)
        <li><a class="nav-link" href="{{ route('page', pagePath($page)) }}">{{ $page->getMenuTitle() }}</a></li>
    @endforeach
    @if ($morePages = array_slice($menuPages, 3))
        <li class="nav-item dropdown">
            <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown"
               aria-haspopup="true" aria-expanded="false">
                ... <span class="caret"></span>
            </a>
            <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                @foreach ($morePages as $page)
                    <a class="dropdown-item"
                       href="{{ route('page', pagePath($page)) }}">{{ $page->getMenuTitle() }}</a>
                @endforeach
            </div>
        </li>
    @endif
</ul>
