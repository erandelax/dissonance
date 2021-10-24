@extends('layouts.default')

@section('title', 'Users')

@section('body')
    <div class="content">
        <h2 class="content-title">
            Project
        </h2>
    </div>
    <div class="container">
        @dump($project)
    </div>
@endsection
