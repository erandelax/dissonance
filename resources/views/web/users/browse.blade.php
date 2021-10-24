@extends('layouts.default')

@section('title', 'Users')

@section('body')
    <div class="content">
        <h2 class="content-title">
            Users list
        </h2>
    </div>
    <div class="container" style="display:flex;flex-direction: row;flex-wrap: wrap;">
        @foreach ($users as $user)
            <a href="{{scoped_route('users.read',['user' => $user->id, 'locale' => app()->getLocale()])}}"
               class="card sponsor-section-card w-350 mw-full m-10 p-0 d-flex" rel="noopener">
                <div class="w-100 h-100 m-10 align-self-center">
                    <img src="{{$user->avatar}}" class="d-block w-100 h-100 rounded">
                </div>
                <div class="flex-grow-1 overflow-y-hidden d-flex align-items-center position-relative h-120">
                    <div class="p-10 w-full m-auto">
                        <p class="font-size-10 text-dark-lm text-light-dm m-0 mb-5 text-truncate font-weight-medium">
                            {{$user->name}}
                        </p>
                        <p class="font-size-12 mt-5 mb-0">
                            {{$user->discord_id}}
                        </p>
                        <p class="font-size-12 mt-5 mb-0">
                            {{$user->created_at->format('Y-m-d H:i:s')}}
                        </p>
                        <p class="font-size-12 mt-5 mb-0">
                                <span class="text-primary text-smoothing-auto-dm d-inline-block">See profile <i
                                        class="fa fa-angle-right" aria-hidden="true"></i></span>
                        </p>
                    </div>
                    <div class="sponsor-section-card-scroll-block"></div>
                </div>
            </a>
        @endforeach
    </div>
@endsection
