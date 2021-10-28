@extends('layouts.default')

@section('title', $page?->title ?? 'Page not found')

@push('scripts')
    <script type="text/javascript" charset="utf-8" src="https://cdn.jsdelivr.net/gh/ajaxorg/ace-builds@latest/src-min-noconflict/ace.js"></script>
@endpush

@php
    /** @var \App\Models\Page $page */
@endphp

@if($page)
@section('actions')
    @include('partials.page_revisions_menu', ['page' => $page])
    <li class="nav-item">
        <label class="nav-link">
            @lang('app.form.save')
            <input type="submit" name="submit" form="page-editor" style="display: none">
        </label>
    </li>
@endsection
@endif

@push('styles')
    <style>
        .ace-tomorrow-night {
            background: transparent;
        }
        #preview {
            max-height: 80vh;
            overflow-y: auto;
        }
    </style>
@endpush

@push('scripts')
    <script>
        window.addEventListener('DOMContentLoaded', function(){
            var theme = "ace/theme/tomorrow_night"
            if (halfmoon.getPreferredMode() === "light-mode" || halfmoon.getPreferredMode() === "not-set") {
                theme = "ace/theme/chrome"
            }
            const editor = ace.edit("field-content-ace", {
                theme: theme,
                mode: "ace/mode/markdown",
                minLines: 10,
                maxLines: 25,
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
            editor.session.on("changeScrollTop", function() {
                const percent = editor.session.getScrollTop() / (editor.session.getScreenLength() * editor.renderer.lineHeight);
                document.getElementById('preview').scrollTop = percent * document.getElementById('preview').scrollHeight;
            })
            document.getElementById('preview').addEventListener('scroll', function(event) {
                const percent = event.target.scrollTop / event.target.scrollHeight;
                editor.session.setScrollTop((editor.session.getScreenLength() * editor.renderer.lineHeight) * percent);
            });
            document.getElementById('aceImage').addEventListener('click', function(e) {
                app.modal.frame('{{scoped_route('uploads.browse', ['locale' => $locale])}}', function(data){
                    editor.insert('![]('+data.id+')')
                });
            });
        })
    </script>
@endpush

@section('body')
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-6">
                    <form action="{{scoped_route('pages.edit', ['page' => $pageReference, 'locale' => app()->getLocale()])}}" enctype="multipart/form-data" method="post" class="p-10 mw-full" id="page-editor">
                        @csrf
                        <div class="form-group">
                            <label for="field-title" class="required">Title</label>
                            <input type="text" class="form-control" id="field-title" placeholder="Full name" name="title" required="required" value="{{$page?->title ?? 'Page not found'}}">
                        </div>
                        <div class="form-group">
                            <label for="field-locale" class="required">Locale</label>
                            <input type="text" class="form-control" id="field-locale" placeholder="Full name" name="locale" required="required" value="{{$page?->locale ?? app()->getLocale()}}">
                        </div>
                        <div class="form-group">
                            <label for="field-slug">Slug</label>
                            <input type="text" class="form-control" id="field-slug" placeholder="Full name" name="slug" value="{{$pageReference}}" disabled>
                        </div>
                        <div class="form-group">
                            <label for="field-content">Content</label>
                            <input type="hidden" class="form-control" id="field-content" name="content" value="{{$page?->content}}">
                            <div class="form-control" style="height:auto">
                                <div id="field-content-ace">{{$page?->content}}</div>
                            </div>
                            <button id="aceImage" type="button" class="btn btn-primary"><i class="fas fa-image"></i></button>
                        </div>
                    </form>
                </div>
                <div class="col-6 m-0 p-0">
                    <div id="preview" >{!! $page?->content !!}</div>
                </div>
            </div>
        </div>
    </div>
@endsection
