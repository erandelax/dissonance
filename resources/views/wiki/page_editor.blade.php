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
    <input type="submit" name="submit" class="menu-item form-submit" value="@lang('app.form.save')" form="page-editor">
@endsection

@section('body')
    <div class="layout-columns">
        <form class="column form" id="page-editor" method="post" action="{{route('wiki.store', ['slug' => $page->slug, 'locale' => app()->getLocale()])}}" enctype="multipart/form-data">
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
            <div class="form-row fill">
                <label>Content</label>
                <input type="hidden" id="editor-input" name="content" value="{{$page->content}}">
                <div class="form-input" style="min-width: 100%;padding:0">
                    <div id="editor">{{$page->content}}</div>
                </div>
            </div>
        </form>
        <div class="column form">
            <div class="form-row">
                <label>Preview</label>
                <div id="preview" class="form-preview form-input article"></div>
            </div>
        </div>
    </div>
    <script>
        const editor = ace.edit("editor", {
            theme: "ace/theme/twilight",
            mode: "ace/mode/markdown",
            minLines: 1,
            maxLines: Infinity,
            //showLineNumbers: false,
            cursorStyle: "smooth",
            fontSize: "14px",
            fontFamily: "monospace",
            highlightActiveLine: true,
            highlightGutterLine: false,
            printMargin: true,
            wrap: true,
            indentedSoftWrap: true,
            showGutter:false,
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
        window.addEventListener('resize',function(){
            editor.resize()
        })
    </script>
@endsection
