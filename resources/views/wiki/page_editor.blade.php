@extends('layouts.default')

@section('title', $page->title)

@push('scripts')
<script type="text/javascript" charset="utf-8" src="https://cdn.jsdelivr.net/gh/ajaxorg/ace-builds@latest/src-min-noconflict/ace.js"></script>
@endpush

@php
/** @var \App\Models\WikiPage $page */
@endphp

@section('actions')
    <input type="submit" class="menu-item form-submit" value="@lang('app.form.save')" form="page-editor">
@endsection

@section('body')
    <div class="layout-center">
        <div class="layout-page">
            <form id="page-editor" class="form" method="post" action="{{route('wiki.store', ['slug' => $page->slug, 'locale' => app()->getLocale()])}}" enctype="multipart/form-data">
                @csrf
                <div class="form-row">
                    <label>Title</label>
                    <input class="form-input" type="text" name="title" value="{{$page->title}}">
                </div>
                <div class="form-row">
                    <label>Locale</label>
                    <input class="form-input" type="text" name="locale" value="{{$page->locale}}">
                </div>
                <div class="form-row">
                    <label>Slug</label>
                    <input class="form-input" type="text" name="slug" value="{{$page->slug}}">
                </div>
                <div class="form-row">
                    <label>Content</label>
                    <input type="hidden" id="editor-input" name="content" value="{{$page->content}}">
                    <div class="form-input" style="min-width: 100%;min-height: 12rem">
                        <div id="editor">{{$page->content}}</div>
                    </div>
                </div>
                <div class="form-row">
                    <label>Preview</label>
                    <div id="preview" class="form-preview form-input article"></div>
                </div>
            </form>
        </div>
    </div>
    <script>
        const editor = ace.edit("editor", {
            theme: "ace/theme/twilight",
            mode: "ace/mode/markdown",
            minLines: 12,
            maxLines: 12,
            //showLineNumbers: false,
            cursorStyle: "smooth",
            fontSize: "14px",
            fontFamily: "monospace",
            highlightActiveLine: false,
            highlightGutterLine: false,
            printMargin: false,
            //showGutter:false,
        })
        let interval = null;
        const refresher = async function() {
            const preview = await api.wiki.preview({
                content: editor.getValue(),
            });
            document.getElementById('preview').innerHTML = preview.content;
        };
        editor.on('change', async function() {
            document.getElementById('editor-input').value = editor.getValue();
            if (interval) {
                clearTimeout(interval);
            }
            interval = setTimeout(refresher, 350);
        })
        refresher();
    </script>
@endsection
