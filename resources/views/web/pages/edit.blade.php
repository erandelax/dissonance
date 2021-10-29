@php
/** @var \App\Components\ModelForm $form */
/** @var \App\Models\Page $page */
$page = $form->getModel();
@endphp

@extends('layouts.default')

@section('title', $page?->title ?? 'Page not found')

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

{{--
document.getElementById('preview').addEventListener('scroll', function(event) {
    const percent = event.target.scrollTop / event.target.scrollHeight;
    editor.session.setScrollTop((editor.session.getScreenLength() * editor.renderer.lineHeight) * percent);
});
document.getElementById('aceImage').addEventListener('click', function(e) {
    app.modal.frame('{{scoped_route('uploads.browse', ['locale' => $locale])}}', function(data){
        editor.insert('![]('+data.id+')')
    });
});
--}}

@section('body')
    <div class="content">
        {!! $form->render() !!}
    </div>
@endsection
