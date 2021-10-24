@extends('layouts.default')

@section('title', $page?->title ?? 'Page not found')

@can('update-page', $page)
@section('actions')
    <li class="nav-item">
        <a href="{{scoped_route('pages.read', ['locale' => app()->getLocale(), 'page' => $pageReference ?? '', 'mode' => 'edit'])}}"
           class="nav-link">{{$page->getKey() !== null ? 'Edit' : 'Create'}}</a>
    </li>
@endsection
@endcan

@section('body')
    <div class="content">
        {!! $html !!}
    </div>
@endsection
