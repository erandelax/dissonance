@if ($page->revisions->isNotEmpty())
<li class="nav-item dropdown with-arrow">
    <a class="nav-link" data-toggle="dropdown" id="nav-link-dropdown-toggle">
        Revisions
        <i class="fa fa-angle-down ml-5" aria-hidden="true"></i>
    </a>
    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="nav-link-dropdown-toggle">
        @foreach($page->revisions as $revision)
            <a
                target="_blank"
                href="{{route('wiki', ['locale' => app()->getLocale(), 'slug' => $page->slug, 'mode' => 'restore', 'revision' => $revision->id])}}"
                class="dropdown-item">
                {{$revision?->created_at?->format('Y-m-d H:i:s')}}, {{$revision?->user?->name}}
            </a>
        @endforeach
    </div>
</li>
@endif
