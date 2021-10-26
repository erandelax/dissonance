<nav class="navbar">
    @yield('navbar')
    {{--
    <ul class="navbar-nav d-none d-lg-flex"> <!-- d-none = display: none, d-lg-flex = display: flex on large screens and up (width > 992px) -->
        <li class="nav-item">
            <a href="{{scoped_route('pages.read', ['locale' => app()->getLocale(), 'page' => '/'])}}" class="nav-link"><i class="fas fa-question"></i>&nbsp;Wiki</a>
        </li>
        <li class="nav-item">
            <a href="{{config('services.discord.invite_uri')}}" class="nav-link"><i class="fab fa-discord"></i>&nbsp;Discord</a>
        </li>
        <button class="btn btn-action mr-5" type="button" onclick="halfmoon.toggleDarkMode()">
            <i class="fa fa-moon-o" aria-hidden="true"></i>
            <span class="sr-only">Toggle dark mode</span>
        </button>
    </ul>
    --}}
</nav>
