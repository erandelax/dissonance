@extends('layouts.default')

@section('title', $page->title)

@push('scripts')
<script type="text/javascript" charset="utf-8" src="https://cdn.jsdelivr.net/gh/ajaxorg/ace-builds@latest/src-min-noconflict/ace.js"></script>
@endpush

@php
/** @var \App\Models\WikiPage $page */
@endphp

@section('body')
    <form class="form">
        @csrf
        <div>
            Title: <input class="form-input" type="text" name="slug" value="{{$page->title}}">
        </div>
        <div>
            Locale: <input class="form-input" type="text" name="slug" value="{{$page->locale}}">
        </div>
        <div>
            Slug: <input class="form-input" type="text" name="slug" value="{{$page->slug}}">
        </div>
        <div style="display: flex; flex-direction: row">
            <div style="min-height: 12rem; min-width: 50%;" class="form-input">
                <textarea name="editor" id="editor">{{$page->content}}</textarea>
            </div>
            <div id="preview" class="form-preview form-input"></div>
        </div>
        <div>
            <input type="submit" value="@lang('app.form.save')">
        </div>
    </form>
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
            if (interval) {
                clearTimeout(interval);
            }
            interval = setTimeout(refresher, 350);
        })
        refresher();
    </script>
@endsection
