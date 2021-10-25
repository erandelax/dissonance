@extends('layouts.sidebar')

@section('title', $title)

@push('sidebar')
    @include('partials.sidebar_admin_menu')
@endpush

@section('body')
    <div class="content">
        {{$form->render()}}
    </div>
@endsection
