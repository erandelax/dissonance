@extends('layouts.default')

@section('title', 'Users')

@section('body')
    <div class="layout-center">
        <div class="layout-page">
            <h1>Users</h1>
            <table style="width:100%;border:1px solid white;border-collapse:collapse">
                <tr>
                @foreach ($users as $user)
                    <td>
                        {{$user->discord_id}}
                    </td>
                    <td style="border:1px solid white">
                        <img class="user-avatar" src="{{$user->avatar}}"/>
                    </td>
                    <td style="border:1px solid white">
                        <a class="menu-item" href="{{route('profile',['discordID' => $user->discord_id, 'locale' => app()->getLocale()])}}">
                            {{$user->name}}
                        </a>
                    </td>
                    <td style="border:1px solid white">
                        {{$user->created_at}}
                    </td>
                @endforeach
                </tr>
            </table>
            <div>
                {!! $users->render() !!}
            </div>
        </div>
    </div>
@endsection
