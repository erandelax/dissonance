@extends('layouts.default')

@section('title', $page->title)

@push('scripts')
<script type="text/javascript" charset="utf-8" src="https://cdn.jsdelivr.net/gh/ajaxorg/ace-builds@latest/src-min-noconflict/ace.js"></script>
@endpush

@php
/** @var \App\Models\Page $page */
@endphp

@section('actions')
    @include('partials.page_revisions_menu', ['page' => $page])
    <li class="nav-item">
        <label class="nav-link">
            @lang('app.form.save')
            <input type="submit" name="submit" form="page-editor" style="display: none">
        </label>
    </li>
@endsection

@push('styles')
<style>
    .ace-tomorrow-night {
        background: transparent;
    }
</style>
@endpush

@push('scripts')
<script>
    window.addEventListener('DOMContentLoaded', function(){
        const editor = ace.edit("field-content-ace", {
            theme: "ace/theme/tomorrow_night",
            mode: "ace/mode/markdown",
            minLines: 10,
            maxLines: 30,
            showLineNumbers: true,
            cursorStyle: "smooth",
            fontSize: "14px",
            fontFamily: "monospace",
            highlightActiveLine: true,
            highlightGutterLine: false,
            printMargin: true,
            wrap: true,
            indentedSoftWrap: true,
            showGutter: false,
        })
        let interval = null;
        const refresher = async function() {
            const preview = await api.wiki.preview({
                content: editor.getValue(),
            });
            document.getElementById('preview').innerHTML = preview.content;
        };
        editor.on('change', async function() {
            document.getElementById('field-content').value = editor.getValue();
            if (interval) {
                clearTimeout(interval);
            }
            interval = setTimeout(refresher, 350);
        })
        refresher();
        window.addEventListener('resize',function(){
            editor.resize()
        })
    })
</script>
@endpush

@section('body')
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-4">
                    <form action="{{route('wiki.store', ['slug' => $page->slug, 'locale' => app()->getLocale()])}}" enctype="multipart/form-data" method="post" class="w-400 mw-full" id="page-editor">
                        @csrf
                        <div class="form-group">
                            <label for="field-title" class="required">Title</label>
                            <input type="text" class="form-control" id="field-title" placeholder="Full name" name="title" required="required" value="{{$page->title}}">
                        </div>
                        <div class="form-group">
                            <label for="field-locale" class="required">Locale</label>
                            <input type="text" class="form-control" id="field-locale" placeholder="Full name" name="locale" required="required" value="{{$page->locale}}">
                        </div>
                        <div class="form-group">
                            <label for="field-slug">Slug</label>
                            <input type="text" class="form-control" id="field-slug" placeholder="Full name" name="slug" value="{{$page->slug}}">
                        </div>
                        <div class="form-group">
                            <label for="field-content">Content</label>
                            <input type="hidden" class="form-control" id="field-content" name="content" value="{{$page->content}}">
                            <div class="form-control" style="height:auto"><div id="field-content-ace">{{$page->content}}</div></div>
                        </div>
                    </form>
                </div>
                <div class="col-6">
                    <div id="preview" class="content">{!! $page->content !!}</div>
                </div>
            </div>
        </div>
    </div>
@endsection
