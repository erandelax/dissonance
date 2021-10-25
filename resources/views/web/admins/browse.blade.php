@extends('layouts.sidebar')

@section('title', 'Admin panel')

@push('sidebar')
@include('partials.sidebar_admin_menu')
@endpush

@section('body')
    <div class="content">
        Admin panel
    </div>
@endsection
