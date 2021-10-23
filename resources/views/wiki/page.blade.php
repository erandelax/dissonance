@extends('layouts.default')

@section('title', $page->title)

@can('update-page', $page)
@section('actions')
<li class="nav-item">
    <a href="{{route('wiki', ['locale' => app()->getLocale(), 'slug' => $page->slug, 'mode' => 'edit'])}}" class="nav-link">Edit</a>
</li>
@endsection
@endcan

@section('body')
    <div class="content">
        {!! $page->html !!}
    </div>
@endsection
