@extends('layouts.default')

@section('title', "{$page->title} [{$revision->created_at}]")

@php
    /** @var \App\Models\Page $page */
@endphp

@section('actions')
    @include('partials.page_revisions_menu', ['page' => $page])
    <input name="submit" type="submit" class="menu-item form-submit" value="@lang('app.form.restore')" form="page-editor">
@endsection

@section('body')
    <div class="layout-columns">
        <form class="column form" id="page-editor" method="post" action="{{route('wiki.store', ['slug' => $page->slug, 'locale' => app()->getLocale()])}}" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="revision" value="{{$revision->id}}">
            <div class="form-row">
                <label>Title</label>
                <input class="form-input" type="text" value="{{$page->title}}" disabled>
            </div>
            <div class="form-row">
                <label>Locale</label>
                <input class="form-input" type="text" value="{{$page->locale}}" disabled>
            </div>
            <div class="form-row">
                <label>Slug</label>
                <input class="form-input" type="text" value="{{$page->slug}}" disabled>
            </div>
            <div class="form-row fill">
                <label>Content</label>
                <div class="form-input" style="min-width: 100%;padding:0">
                    <div id="editor">
                        {!! $diff !!}
                    </div>
                </div>
            </div>
        </form>
        <div class="column form">
            <div class="form-row">
                <label>Preview</label>
                <div id="preview" class="form-preview form-input article">
                    {!! $html !!}
                </div>
            </div>
        </div>
    </div>
@endsection
