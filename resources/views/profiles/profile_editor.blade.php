@extends('layouts.default')

@section('title', $user->nickname)

@php
use Illuminate\Support\Facades\Session;
@endphp
@section('actions')
    <input type="submit" class="menu-item form-submit" value="@lang('app.form.save')" form="profile-editor">
@endsection
@section('body')
    <div class="layout-center">
        <div class="layout-page">
            <a href="{{route('oauth.logout')}}">@lang('app.auth.logout')</a>
            <form id="profile-editor" class="form" method="post" action="{{route('profile', ['discordID' => $user->discord_id, 'locale' => app()->getLocale()])}}" enctype="multipart/form-data">
                @csrf
                <p>
                    <label for="profile-avatar" style="cursor:pointer"><img id="profile-avatar-preview" class="user-avatar--large" src="{{$user->avatar}}"/></label>
                    <input name="avatar" id="profile-avatar" style="display: none" type="file" accept="image/*">
                    @error('avatar')
                    <span class="error" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </p>
                <p>Discord: <a target="blank" href="https://discordapp.com/users/{{$user->discord_id}}">{{$user->nickname}}</a></p>
                <p>@lang('app.profile.registered_at'): {{$user->created_at}}</p>
                <p>@lang('app.profile.name'): <input name="name" class="form-field" type="text" value="{{$user->name}}"></p>
                <p>@lang('app.profile.locale'): {{$user->locale}}</p>
                @error('name')
                <span class="error" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </form>
            <script>
                document.getElementById('profile-avatar').addEventListener('change', function(e) {
                    const file = e.target.files[0];
                    if (file !== undefined) {
                        document.getElementById('profile-avatar-preview').src = URL.createObjectURL(file);
                    }
                })
            </script>
        </div>
    </div>
@endsection
