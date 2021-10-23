@extends('layouts.default')

@section('title', $page->title)

@can('update-page')
@section('actions')
<a class="menu-item" href="{{route('wiki', ['locale' => app()->getLocale(), 'slug' => $page->slug, 'mode' => 'edit'])}}">Edit</a>
@endsection
@endcan

@section('body')

    <div class="layout-center">
        <div class="layout-page article">
        {!! $page->html !!}
        </div>
    </div>
@endsection
