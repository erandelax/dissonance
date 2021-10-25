<div class="sidebar">
    <div class="sidebar-menu">
        @if($search)
        <form class="sidebar-content" method="get" action="{{scoped_route('search.read', ['locale' => app()->getLocale()])}}">
            <input type="text" class="form-control" placeholder="Search" name="q" value="{{request()->get('q')}}">
            <input type="submit" name="submit" form="profile-editor" style="display: none">
            <div class="mt-10 font-size-12">
                Press <kbd>Enter</kbd> to search
            </div>
        </form>
        @endif
        @stack('sidebar')
        {{--<h5 class="sidebar-title">Getting started</h5>
        <div class="sidebar-divider"></div>
        <a href="#" class="sidebar-link active">Installation</a>
        <a href="#" class="sidebar-link">CLI commands</a>
        <br>--}}
        <h5 class="sidebar-title">{{config('app.name')}}</h5>
        <div class="sidebar-divider"></div>
        <a href="{{scoped_route('users.browse', ['locale' => app()->getLocale()])}}" class="sidebar-link"><i class="fas fa-users"></i>&nbsp;Users</a>
        <a href="{{scoped_route('pages.read', ['locale' => app()->getLocale(), 'page' => '/'])}}" class="sidebar-link"><i class="fas fa-question"></i>&nbsp;Wiki</a>
    </div>
</div>
