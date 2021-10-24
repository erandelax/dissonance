@extends('layouts.default')

@section('title', "{$page->title} [{$revision->created_at}]")

@php
    /** @var \App\Models\Page $page */
@endphp

@can('update-page', $page)
@section('actions')
    @include('partials.page_revisions_menu', ['page' => $page])
    <li class="nav-item">
        <label class="nav-link">
            @lang('app.form.restore')
            <input type="submit" name="submit" form="page-editor" style="display: none">
        </label>
    </li>
@endsection
@endcan

@push('styles')
    <style>
        .diff-wrapper tr[data-type='-'] {
            background: rgba(255,0,0,.1)
        }
        .diff-wrapper tr[data-type='+'] {
            background: rgba(0,255,0,.1)
        }
        .diff-wrapper tr[data-type='!'] {
            background: rgba(0,0,0,.05);
        }
        .diff-wrapper del, .diff-wrapper ins {
            text-decoration: none;
        }
        .diff-wrapper del {
            background: rgba(255,0,0,.1);
        }
        .diff-wrapper ins {
            background: rgba(0,255,0,.1);
        }
    </style>
@endpush

@section('body')
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-6">
                    <form action="{{scoped_route('pages.edit', ['page' => $page->slug, 'locale' => app()->getLocale()])}}" enctype="multipart/form-data" method="post" class="p-10 mw-full" id="page-editor">
                        @csrf
                        <input type="hidden" name="revision" value="{{$revision->id}}">
                        <div class="form-group">
                            <label for="field-title" class="required">Title</label>
                            <input type="text" class="form-control" id="field-title" placeholder="Full name" name="title" required="required" value="{{$page->title}}" disabled>
                        </div>
                        <div class="form-group">
                            <label for="field-locale" class="required">Locale</label>
                            <input type="text" class="form-control" id="field-locale" placeholder="Full name" name="locale" required="required" value="{{$page->locale}}" disabled>
                        </div>
                        <div class="form-group">
                            <label for="field-slug">Slug</label>
                            <input type="text" class="form-control" id="field-slug" placeholder="Full name" name="slug" value="{{$page->slug}}" disabled>
                        </div>
                        <div class="form-group">
                            <label for="field-content">Content</label>
                            <div class="form-control" style="height:auto">
                                {!! $diff !!}
                            </div>
                        </div>
                    </form>
                </div>
                <div class="col-6">
                    <div id="preview" class="content">{!! $html !!}</div>
                </div>
            </div>
        </div>
    </div>
@endsection
