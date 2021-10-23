<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" style="background:#111">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
    <meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" name="viewport"/>
    <meta name="viewport" content="width=device-width"/>

    <link rel="icon" href="path/to/fav.png">
    <title>@yield('title') - {{ config('app.name') }}</title>

    @routes

    <link href="https://cdnjs.cloudflare.com/ajax/libs/normalize/8.0.1/normalize.min.css" type="text/css"
          rel="stylesheet">
    <link href="{{asset('vendor/halfmoon/css/halfmoon-variables.css')}}" rel="stylesheet"/>
    <script src="https://kit.fontawesome.com/7bc3a55a74.js" crossorigin="anonymous"></script>
    <script src="{{asset('js/app.js')}}"></script>
    <style>
        p img {
            max-width: 100%;
        }
    </style>
    @stack('styles')
    @stack('scripts')
</head>
<body class="with-custom-webkit-scrollbars with-custom-css-scrollbars" data-dm-shortcut-enabled="true" data-sidebar-shortcut-enabled="true" data-set-preferred-mode-onload="true">
<!-- Modals go here -->
<!-- Reference: https://www.gethalfmoon.com/docs/modal -->

<!-- Page wrapper start -->
<div class="page-wrapper with-navbar with-sidebar with-navbar-fixed-bottom" data-sidebar-type="overlayed-all">

    <!-- Sticky alerts (toasts), empty container -->
    <!-- Reference: https://www.gethalfmoon.com/docs/sticky-alerts-toasts -->
    <div class="sticky-alerts"></div>

    <!-- Navbar start -->
    <nav class="navbar">
        <div class="navbar-content">
            <button id="toggle-sidebar-btn" class="btn btn-action" type="button" onclick="halfmoon.toggleSidebar()">
                <i class="fa fa-bars" aria-hidden="true"></i>
            </button>
        </div>
        <a href="{{route('home')}}" class="navbar-brand ml-10 ml-sm-20">
            <img src="{{config('app.logo')}}" alt="fake-logo">
            <span class="d-none d-sm-flex">{{config('app.name')}}</span>
        </a>
        <ul class="navbar-nav d-none d-lg-flex"> <!-- d-none = display: none, d-lg-flex = display: flex on large screens and up (width > 992px) -->
            <li class="nav-item">
                <a href="{{route('wiki', ['locale' => app()->getLocale()])}}" class="nav-link"><i class="fas fa-question"></i>&nbsp;Wiki</a>
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
            <form class="form-inline d-none d-lg-flex ml-auto" action="{{route('search', ['locale' => app()->getLocale()])}}" method="get">
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
                    <a href="{{route('oauth.discord')}}" class="dropdown-item">Login via&nbsp;<i class="fab fa-discord"></i>&nbsp;Discord</a>
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
                    <a href="{{route('profile', ['discordID' => $user->discord_id, 'locale' => app()->getLocale()])}}" class="dropdown-item">My Profile</a>
                    <div class="dropdown-divider"></div>
                    <a href="{{route('oauth.logout')}}" class="dropdown-item">Logout</a>
                </div>
            </li>
            @endauth
            <button class="btn btn-action mr-5" type="button" onclick="halfmoon.toggleDarkMode()">
                <i class="fa fa-moon-o" aria-hidden="true"></i>
                <span class="sr-only">Toggle dark mode</span>
            </button>
        </ul>
    </nav>
    <!-- Navbar end -->

    <!-- Sidebar overlay -->
    <div class="sidebar-overlay" onclick="halfmoon.toggleSidebar()"></div>

    <!-- Sidebar start -->
    <div class="sidebar">
        <div class="sidebar-menu">
            <form class="sidebar-content" method="get" action="{{route('search', ['locale' => app()->getLocale()])}}">
                <input type="text" class="form-control" placeholder="Search" name="q" value="{{request()->get('q')}}">
                <input type="submit" name="submit" form="profile-editor" style="display: none">
                <div class="mt-10 font-size-12">
                    Press <kbd>Enter</kbd> to search
                </div>
            </form>
            {{--<h5 class="sidebar-title">Getting started</h5>
            <div class="sidebar-divider"></div>
            <a href="#" class="sidebar-link active">Installation</a>
            <a href="#" class="sidebar-link">CLI commands</a>
            <br>--}}
            <h5 class="sidebar-title">{{config('app.name')}}</h5>
            <div class="sidebar-divider"></div>
            <a href="{{route('profile.list', ['locale' => app()->getLocale()])}}" class="sidebar-link"><i class="fas fa-users"></i>&nbsp;Users</a>
            <a href="{{route('wiki', ['locale' => app()->getLocale()])}}" class="sidebar-link"><i class="fas fa-question"></i>&nbsp;Wiki</a>
        </div>
    </div>
    <!-- Sidebar end -->

    <!-- Content wrapper start -->
    <div class="content-wrapper">
        <!--
          Add your page's main content here
          Examples:
          1. https://www.gethalfmoon.com/docs/content-and-cards/#building-a-page
          2. https://www.gethalfmoon.com/docs/grid-system/#building-a-dashboard
        -->
        @yield('body')
    </div>
    <!-- Content wrapper end -->

    <!-- Navbar fixed bottom start -->
    <nav class="navbar navbar-fixed-bottom">
        <!-- Reference: https://www.gethalfmoon.com/docs/navbar#navbar-fixed-bottom -->
    </nav>
    <!-- Navbar fixed bottom end -->

</div>
<!-- Page wrapper end -->

<!-- POST-BODY-SCRIPTS -->
<script src="{{asset('vendor/halfmoon/js/halfmoon.min.js')}}"></script>
<script>
    window.addEventListener('DOMContentLoaded', function () {
    })
</script>
</body>
</html>
