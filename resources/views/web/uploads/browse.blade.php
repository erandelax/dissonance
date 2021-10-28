@extends('layouts.iframe')

@section('title', 'Users')

@push('styles')
<style>
.btn-card {
    cursor: pointer;
}
.btn-card:hover {
    filter: brightness(1.1);
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function(){
    if (window.app.isFrame()) {
        for (const upload of document.querySelectorAll('[data-upload-id]')) {
            upload.addEventListener('click', function(event){
                window.app.modal.frame.reply({
                    id: upload.dataset.uploadId,
                    url: upload.dataset.uploadUrl,
                });
            })
        }
    }
})
</script>
@endpush

@php
$iframe = request()->input('iframe');
$items = $uploads->items();
@endphp

@section('navbar')
<form method="post" action="{{scoped_route('uploads.push', ['locale' => $locale, 'iframe' => $iframe])}}" enctype="multipart/form-data" class="navbar-nav d-lg-flex w-full">
    @csrf
    <label class="btn btn-action m-0 w-full" type="button" for="field-upload">
        <i class="fas fa-upload" aria-hidden="true"></i>&nbsp;Upload files
        <input id="field-upload" multiple="multiple" name="uploads[]" type="file" class="d-none" onchange="event.target.form.submit()">
    </label>
</form>
@endsection

@section('statusbar')
{{$uploads->render('partials.pagination')}}
@endsection

@section('body')
    <div class="container-fluid d-flex flex-wrap" style="justify-content: center">
        @foreach ($items as $upload)
        <div class="w-200 mw-full" data-upload-id="{{$upload->id}}"  data-upload-url="{{$upload->preview_url}}">
            <div class="btn-card card m-10 p-0"> <!-- p-0 = padding: 0 -->
                <img src="{{$upload->preview_url}}" class="h-150 w-300 img-fluid rounded-top" style="object-fit: cover">
                <!-- Nested content container inside card -->
                <div class="content m-10 mt-0">
                    <h2 class="content-title m-0" style="text-overflow: ellipsis;overflow: hidden;white-space: nowrap;">
                        {{$upload->name}}
                    </h2>
                    <p class="text-muted m-0">
                        {{$upload->mime}}, {{$upload->size}}b
                    </p>
                </div>
            </div>
        </div>
        @endforeach
    </div>
@endsection
