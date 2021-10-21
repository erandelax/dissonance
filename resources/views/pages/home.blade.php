@extends('layouts.default')

@section('title', 'Home')

@section('body')
    <div class="layout-center">
        <div class="layout-page">
            <h1>{{config('app.name')}}</h1>
            <ul>
                <li>
                    <a href="{{route('wiki', ['locale' => app()->getLocale()])}}">Wiki</a>
                </li>
                <li>
                    <a href="{{config('services.discord.invite_uri')}}" target="_blank">Discord</a>
                </li>
            </ul>
        </div>
    </div>
@endsection
