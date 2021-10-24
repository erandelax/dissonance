@extends('layouts.sidebar')

@section('title', $user->nickname)

@section('body')
    <div class="content">
        <form action="{{scoped_route('users.edit', ['user' => $user->id, 'locale' => app()->getLocale()])}}"
              enctype="multipart/form-data" method="post" class="w-400 mw-full" id="profile-editor">
            @csrf
            <div class="form-group">
                <label for="picture">Avatar</label>
                <div class="container">
                    <div class="row">
                        <div class="col-4">
                            <img id="field-avatar-preview" src="{{$user->avatar}}" class="img-fluid rounded-circle" alt="rounded circle image">
                        </div>
                    </div>
                </div>
                <div class="form-text">
                    Must be PNG up to 10 MB
                </div>
            </div>
            <div class="form-group">
                <label for="field-name" class="required">Username</label>
                <input type="text" class="form-control" name="name" id="field-name" placeholder="Username" required="required" value="{{$user->name}}" disabled>
            </div>
            <div class="form-group">
                <label for="field-locale" class="required">Locale</label>
                <select class="form-control" id="field-locale" required="required" disabled>
                    @foreach(['en' => 'English','ru' => 'Russian'] as $locale => $label)
                        <option value="{{$locale}}" @if($user->locale === $locale)selected="selected" @endif>{{$label}}</option>
                    @endforeach
                </select>
            </div>
            {{--<div class="form-group">
                <label for="field-email">Email</label>
                <input type="text" class="form-control" id="field-email" placeholder="Locale"value="{{$user->email}}" disabled>
            </div>--}}
            <div class="form-group">
                <label for="field-discord_id">DiscordID</label>
                <input type="text" class="form-control" id="field-discord_id" placeholder="DiscordID" value="{{$user->discord_id}}" disabled>
            </div>
            <div class="form-group">
                <label for="field-discord_id">Registration date</label>
                <input type="text" class="form-control" id="field-discord_id" placeholder="DiscordID" value="{{$user->created_at->format('Y-m-d H:i:s')}}" disabled>
            </div>
        </form>
    </div>
    {{--
    <div class="layout-center">
        <div class="layout-page">
            <img class="user-avatar--large" src="{{$user->avatar}}"/>
            <p>Discord: <a target="blank" href="https://discordapp.com/users/{{$user->discord_id}}">{{$user->nickname}}</a></p>
            <p>Name: {{$user->name}}</p>
            <p>Registered: {{$user->created_at}}</p>
        </div>
    </div>
    --}}
@endsection
