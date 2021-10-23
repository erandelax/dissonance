@extends('layouts.default')

@section('title', 'Home')

@section('body')
    <div class="content">
        <h2 class="content-title">
            {{config('app.name')}}
        </h2>
        <p>
            Welcome message placeholder. Ehe.
        </p>
        <div class="alert alert-danger filled-lm" role="alert">
            <h4 class="alert-heading">Site is under construction</h4>
            Everything might get changed very soon and quite drastically.
        </div>
    </div>
@endsection
