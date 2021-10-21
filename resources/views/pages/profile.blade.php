@extends('layouts.default')

@section('title', $user->nickname)

@section('body')
    <div class="layout-center">
        <div class="layout-page">
            <img class="user-avatar--large" src="{{$user->avatar}}"/>
            <p>Discord: <a target="blank" href="https://discordapp.com/users/{{$user->discord_id}}">{{$user->nickname}}</a></p>
            <p>Name: {{$user->name}}</p>
            <p>Registered: {{$user->created_at}}</p>
        </div>
    </div>
@endsection
