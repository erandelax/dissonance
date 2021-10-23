@extends('layouts.default')

@section('title', 'Characters and organizations')

@section('body')
    <div class="content">
        <h2 class="content-title">
            Characters and organizations
        </h2>
    </div>
    <div class="container" style="display:flex;flex-direction: row;flex-wrap: wrap;">
        TODO
        @foreach ($characters as $character)
            TODO
        @endforeach
    </div>
@endsection
