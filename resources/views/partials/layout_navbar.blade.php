<nav class="navbar">
    <div class="navbar-content">
        <button id="toggle-sidebar-btn" class="btn btn-action" type="button" onclick="halfmoon.toggleSidebar()">
            <i class="fa fa-bars" aria-hidden="true"></i>
        </button>
    </div>
    <a href="{{scoped_route('projects.browse')}}" class="navbar-brand ml-10 ml-sm-20">
        <img src="{{$config->get('app.logo', config('app.logo'))}}" alt="fake-logo">
        <span class="d-none d-sm-flex">{{$config->get('app.name', config('app.name'))}}</span>
    </a>
    <ul class="navbar-nav d-none d-lg-flex"> <!-- d-none = display: none, d-lg-flex = display: flex on large screens and up (width > 992px) -->
        <li class="nav-item">
            <a href="{{scoped_route('pages.read', ['locale' => app()->getLocale(), 'page' => '/'])}}" class="nav-link"><i class="fas fa-question"></i>&nbsp;Wiki</a>
        </li>
        <li class="nav-item">
            <a href="{{config('services.discord.invite_uri')}}" class="nav-link"><i class="fab fa-discord"></i>&nbsp;Discord</a>
        </li>
    </ul>
    <ul class="navbar-nav ml-auto"> <!-- ml-auto = margin-left: auto -->
        {{--
        <li class="nav-item active">
            <a href="#" class="nav-link">Docs</a>
        </li>
        --}}
        <form class="form-inline d-none d-lg-flex ml-auto" action="{{scoped_route('search.read', ['locale' => app()->getLocale()])}}" method="get">
            <input type="text" class="form-control" placeholder="Search" required="required" id="navbar-search" name="q" value="{{request()->get('q')}}">
            <label class="nav-link">
                <i class="fa fa-search"></i>
                <input type="submit" name="submit" style="display: none">
            </label>
        </form>
        @yield('actions')
        @guest
            <li class="nav-item dropdown with-arrow">
                <a class="nav-link" data-toggle="dropdown" id="nav-link-dropdown-toggle">
                    Guest
                    <i class="fa fa-angle-down ml-5" aria-hidden="true"></i> <!-- ml-5= margin-left: 0.5rem (5px) -->
                </a>
                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="nav-link-dropdown-toggle">
                    <a href="{{scoped_route('auths.discord', ['returnURL' => url()->current()])}}" class="dropdown-item">Login via&nbsp;<i class="fab fa-discord"></i>&nbsp;Discord</a>
                    {{--<a href="#" class="dropdown-item">Functions</a>
                    <a href="#" class="dropdown-item">
                        Analytics
                        <strong class="badge badge-success float-right">New</strong> <!-- float-right = float: right -->
                    </a>
                    <div class="dropdown-divider"></div>
                    <div class="dropdown-content">
                        <a href="#" class="btn btn-block" role="button">
                            See all products
                            <i class="fa fa-angle-right ml-5" aria-hidden="true"></i> <!-- ml-5= margin-left: 0.5rem (5px) -->
                        </a>
                    </div>
                    --}}
                </div>
            </li>
        @endguest
        @auth
            @php($user = \Illuminate\Support\Facades\Auth::user())
            <li class="nav-item dropdown with-arrow">
                <a class="nav-link" data-toggle="dropdown" id="nav-link-dropdown-toggle">
                    <img src="{{$user->avatar}}" class="img-fluid rounded-circle" alt="rounded circle image" style="max-width: 24px;margin-right:1rem;">
                    <span>{{$user->name}}</span>
                    <i class="fa fa-angle-down ml-5" aria-hidden="true"></i> <!-- ml-5= margin-left: 0.5rem (5px) -->
                </a>
                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="nav-link-dropdown-toggle">
                    <a href="{{scoped_route('users.read', ['user' => $user->id, 'locale' => app()->getLocale()])}}" class="dropdown-item">My Profile</a>
                    <div class="dropdown-divider"></div>
                    <a href="{{scoped_route('admins.browse', ['locale' => app()->getLocale()])}}" class="dropdown-item">Admin panel</a>
                    <div class="dropdown-divider"></div>
                    <a href="{{scoped_route('auths.logout')}}" class="dropdown-item">Logout</a>
                </div>
            </li>
        @endauth
        <button class="btn btn-action mr-5" type="button" onclick="halfmoon.toggleDarkMode()">
            <i class="fa fa-moon-o" aria-hidden="true"></i>
            <span class="sr-only">Toggle dark mode</span>
        </button>
    </ul>
</nav>
