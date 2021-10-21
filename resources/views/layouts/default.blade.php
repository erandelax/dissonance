<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" style="background:#111">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title') - {{ config('app.name') }}</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/normalize/8.0.1/normalize.min.css" type="text/css" rel="stylesheet">
    @routes
    <script>
        class Card extends HTMLElement {
            constructor() {
                super();
            }
            connectedCallback(){
                this.attachShadow({mode: 'open'});
                const card = document.createElement('DIV');
                card.classList.add('wiki-card');
                card.innerHTML = '<div style="border-bottom:1px solid white;padding:.1rem .2rem;">About</div><div style="padding:.1rem .2rem;">Just something about it</div>';
                const style = document.createElement('style');
                style.textContent = '.wiki-card { border: 1px solid white; float:right; min-width: 180px; display:flex; flex-direction: column }';
                const image = this.getAttribute('img')
                console.log(this)
                if (image) {
                    const img = document.createElement('IMG');
                    img.src = image;
                    card.prepend(img)
                }
                this.shadowRoot.append(style, card);
            }
        }
        customElements.define('wiki-card', Card);
    </script>
    <style>
        html {
            font-family: monospace;
            padding: 0;
            margin: 0;
            background: #131313;
            color: #ddd;
        }
        a {
            color: #fff;
            text-decoration: none;
        }
        html, body {
            width: 100%;
            height: 100%;
            overflow: hidden;
        }
        body {
            overflow-y: auto;
            display: flex;
            flex-direction: column;
        }
        .layout-center, .layout-status-bar {
            width: 100%;
        }
        .user-avatar, .user-avatar--large {
            border-radius: 50%;
            object-fit: contain;
        }
        .user-avatar {
            width: 24px;
            height: 24px;
            margin-right: .5rem;
        }
        .user-avatar--large {
            height: 128px;
            width: 128px;
        }
        .menu {
            display: flex;
        }
        .menu-horizontal {
            background: #222;
            flex-direction: row;
            justify-content: space-between;
        }
        .menu .menu-item {
            padding: .25rem .75rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-direction: row;
        }
        .menu a.menu-item, .menu input.form-submit {
            transition: all .2s;
            background: #333;
            border: none;
            color: #fff;
            cursor: pointer;
        }
        .menu a.menu-item:hover, .menu input.form-submit:hover {
            background: #999;
        }
        .menu input.form-submit {
            background: #533;
        }
        .menu input.form-submit:hover {
            background: #b99;
        }
        .form-input {
            background: #131313;
            color: white;
            border: none;
            border-bottom: 1px solid white;
            outline: none;
            display: inline-block;
        }
        .form-preview {
            background: #111;
            padding: 1rem 2rem;
            border: none;
        }
        .layout-center {
            max-width: 1024px;
            margin: 0 auto;
        }
        .form-row {
            display: flex;
            flex-direction: column;
            margin-top: 1rem;
        }
        .form-row label {
            font-size: 1.1rem;
        }
        .form-row .form-input {
            background: #181818;
            padding: .5rem;
        }
        .layout-page {
            width: 100%;
        }
        .article {
            padding: 1rem 2rem ;
        }
        .article a {
            text-decoration: underline;
        }
    </style>
    <script src="{{asset('js/app.js')}}"></script>
    @stack('styles')
    @stack('scripts')
</head>
<body>
    <div class="layout-status-bar menu menu-horizontal">
        <span class="menu menu-horizontal">
            <a class="menu-item" href="{{route('home', ['locale' => app()->getLocale()])}}">{{config('app.name')}}</a>
            <a class="menu-item" href="{{config('services.discord.invite_uri')}}" target="_blank">Discord</a>
            <a class="menu-item" href="{{route('wiki', ['locale' => app()->getLocale()])}}">Wiki</a>
        </span>
        <span class="menu menu-horizontal">
            <span class="menu-item">@yield('title')</span>
        </span>
        <span class="menu menu-horizontal">
        @yield('actions')
        @auth
            @php
            $user = \Illuminate\Support\Facades\Auth::user();
            @endphp
            <a class="menu-item" href="{{route('profile',['discordID' => $user->discord_id, 'locale' => app()->getLocale()])}}">
                <img class="user-avatar" src="{{$user->avatar}}"/><div>{{$user->name}}</div>
            </a>
        @endauth
        @guest
            <a class="menu-item" href="{{route('oauth.discord')}}">
                <img class="user-avatar" src=""/><div>{{__('app.auth.guest')}}</div>
            </a>
        @endguest
        </span>
    </div>
    @yield('body')
</body>
</html>
