<a href="{{scoped_route('admins.browse', ['locale' => $locale])}}" class="sidebar-link"><i class="fas fa-tachometer-alt"></i>&nbsp;Dashboard</a>
<br />
<h5 class="sidebar-title">{{config('app.name')}}</h5>
<div class="sidebar-divider"></div>
<a href="{{scoped_route('admins.read', ['locale' => $locale, 'page' => 'settings'])}}" class="sidebar-link"><i class="fas fa-cog"></i>&nbsp;Settings</a>
<div class="sidebar-divider"></div>
<a href="{{scoped_route('admins.read', ['locale' => $locale, 'page' => 'projects'])}}" class="sidebar-link"><i class="fas fa-boxes"></i>&nbsp;Projects</a>
<a href="{{scoped_route('admins.read', ['locale' => $locale, 'page' => 'users'])}}" class="sidebar-link"><i class="fas fa-address-card"></i>&nbsp;Users</a>
<div class="sidebar-divider"></div>
<a href="{{scoped_route('admins.read', ['locale' => $locale, 'page' => 'pages'])}}" class="sidebar-link"><i class="fas fa-file-alt"></i>&nbsp;Pages</a>
<a href="{{scoped_route('admins.read', ['locale' => $locale, 'page' => 'posts'])}}" class="sidebar-link"><i class="fas fa-newspaper"></i>&nbsp;Posts</a>
<a href="{{scoped_route('admins.read', ['locale' => $locale, 'page' => 'uploads'])}}" class="sidebar-link"><i class="fas fa-upload"></i>&nbsp;Uploads</a>
@if($project)
<br />
<h5 class="sidebar-title">{{$project->display_name}}</h5>
<div class="sidebar-divider"></div>
<a href="{{scoped_route('admins.read', ['locale' => $locale, 'page' => 'project-settings'])}}" class="sidebar-link"><i class="fas fa-cog"></i>&nbsp;Settings</a>
<div class="sidebar-divider"></div>
<a href="{{scoped_route('admins.read', ['locale' => $locale, 'page' => 'project-settings'])}}" class="sidebar-link"><i class="fas fa-address-card"></i>&nbsp;Users</a>
<div class="sidebar-divider"></div>
<a href="{{scoped_route('admins.read', ['locale' => $locale, 'page' => 'project-pages'])}}" class="sidebar-link"><i class="fas fa-file-alt"></i>&nbsp;Pages</a>
<a href="{{scoped_route('admins.read', ['locale' => $locale, 'page' => 'project-posts'])}}" class="sidebar-link"><i class="fas fa-newspaper"></i>&nbsp;Posts</a>
<a href="{{scoped_route('admins.read', ['locale' => $locale, 'page' => 'project-uploads'])}}" class="sidebar-link"><i class="fas fa-upload"></i>&nbsp;Uploads</a>
<br />
@endif
