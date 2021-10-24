@extends('layouts.sidebar')

@section('title', $page?->title ?? 'Page not found')

@can('update-page', $page)
@section('actions')
    <li class="nav-item">
        <a href="{{scoped_route('pages.read', ['locale' => app()->getLocale(), 'page' => $pageReference ?? '', 'mode' => 'edit'])}}"
           class="nav-link">{{$page->getKey() !== null ? 'Edit' : 'Create'}}</a>
    </li>
@endsection
@endcan

@if($headers)
@push('sidebar')
<h5 class="sidebar-title">{{$page?->title ?? 'Page'}}</h5>
<div class="sidebar-divider"></div>
@foreach ($headers as $header)
<a href="#{{$header['id']}}" class="sidebar-link"><i class="fas fa-ellipsis-h" style="margin-left: {{$header['level'] * 0.5}}rem"></i>&nbsp;{{$header['header']}}</a>
@endforeach
@endpush
@endif

@section('body')
    <div class="content">
        {!! $html !!}
    </div>
@endsection
