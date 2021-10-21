@extends('layouts.default')

@section('title', $page->title)

@section('actions')
<a class="menu-item" href="{{route('wiki', ['locale' => app()->getLocale(), 'slug' => $page->slug, 'mode' => 'edit'])}}">Edit</a>
@endsection

@section('body')

    <div class="layout-center">
        <div class="layout-page article">
        @if (null === $page)
                ERROR: PAGE NOT FOUND
        @else
            {!! $page->html !!}
        @endif
        </div>
    </div>
@endsection
