@extends('layouts.default')

@section('title', $user->nickname)
@push('scripts')
<script>
    window.addEventListener('DOMContentLoaded', function(){
        document.getElementById('field-avatar').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file !== undefined) {
                document.getElementById('field-avatar-preview').src = URL.createObjectURL(file);
            }
        })
    });
</script>
@endpush
@php
    use Illuminate\Support\Facades\Session;
@endphp
@section('actions')
    <li class="nav-item">
        <label class="nav-link">
            @lang('app.form.save')
            <input type="submit" name="submit" form="profile-editor" style="display: none">
        </label>
    </li>
@endsection
@section('body')
    <div class="content">
        <form action="{{route('profile', ['discordID' => $user->discord_id, 'locale' => app()->getLocale()])}}"
              enctype="multipart/form-data" method="post" class="w-400 mw-full" id="profile-editor">
            @csrf
            <div class="form-group">
                <label for="picture">Avatar</label>
                <div class="container">
                    <div class="row">
                        <div class="col-4">
                            <img id="field-avatar-preview" src="{{$user->avatar}}" class="img-fluid rounded-circle" alt="rounded circle image">
                        </div>
                        <div class="col-6">
                            <div class="custom-file content">
                                <input type="file" id="field-avatar" name="avatar">
                                <label for="field-avatar">Choose picture</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-text">
                    Must be PNG up to 10 MB
                </div>
            </div>
            <div class="form-group">
                <label for="field-name" class="required">Username</label>
                <input type="text" class="form-control" name="name" id="field-name" placeholder="Username" required="required" value="{{$user->name}}">
                <div class="form-text">
                    Only alphanumeric characters and underscores allowed.
                </div>
            </div>
            <div class="form-group">
                <label for="field-locale" class="required">Locale</label>
                <select class="form-control" id="field-locale" required="required" name="locale" disabled>
                    @foreach(['en' => 'English','ru' => 'Russian'] as $locale => $label)
                        <option value="{{$locale}}" @if($user->locale === $locale)selected="selected" @endif>{{$label}}</option>
                    @endforeach
                </select>
                <div class="form-text">
                    Only predefined locales can be chosen.
                </div>
            </div>
            <div class="form-group">
                <label for="field-email">Email</label>
                <input type="text" class="form-control" id="field-email" placeholder="Locale" value="{{$user->email}}" disabled>
                <div class="form-text">
                    Is provided by Discord and thus can't be changed.
                </div>
            </div>
            <div class="form-group">
                <label for="field-discord_id">DiscordID</label>
                <input type="text" class="form-control" id="field-discord_id" placeholder="DiscordID" value="{{$user->discord_id}}" disabled>
                <div class="form-text">
                    Is provided by Discord and thus can't be changed.
                </div>
            </div>
            <div class="form-group">
                <label for="field-discord_id">Registration date</label>
                <input type="text" class="form-control" id="field-discord_id" placeholder="DiscordID" value="{{$user->created_at->format('Y-m-d H:i:s')}}" disabled>
            </div>
        </form>
    </div>
@endsection
