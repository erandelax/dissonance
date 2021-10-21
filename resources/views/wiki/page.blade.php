@extends('layouts.default')

@section('title', 'Page')

@section('body')
    @if (null === $page)
            PAGE NOT FOUND
    @else
            {{$page->title}}
        <hr>
            {!! $page->content !!}
    @endif
@endsection
