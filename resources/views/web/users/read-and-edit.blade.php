@extends('layouts.sidebar')

@section('title', $user->name)

@section('body')
    <div class="content">
        {{$form->render()}}
    </div>
@endsection
