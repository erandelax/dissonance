@extends('layouts.default')

@section('title', 'Users')

@section('body')
    <div class="content">
        <h2 class="content-title">
            Projects
        </h2>
    </div>
    <div class="container">
        @foreach ($projects as $project)
            <a href="{{route('subdomain:projects.read', ['project' => $project->reference, 'locale' => app()->getLocale()])}}">
                {{$project->name ?? $project->reference}}
            </a>
        @endforeach
    </div>
@endsection
