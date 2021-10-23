<div class="menu-item menu-popup">
    <span>Revisions</span>
    <div class="menu">
        @foreach($page->revisions as $revision)
            <a target="_blank" class="menu-item" href="{{route('wiki', ['locale' => app()->getLocale(), 'slug' => $page->slug, 'mode' => 'restore', 'revision' => $revision->id])}}">{{$revision?->created_at?->format('Y-m-d H:i:s')}}, {{$revision?->user?->name}}</a>
        @endforeach
    </div>
</div>
