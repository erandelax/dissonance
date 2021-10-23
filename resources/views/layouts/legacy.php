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
                <img class="user-avatar" src="https://www.gravatar.com/avatar"/><div>{{__('app.auth.guest')}}</div>
            </a>
        @endguest
        </span>
</div>
