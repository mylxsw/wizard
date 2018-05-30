@extends('layouts.default')

@section('title', $project->name)
@section('container-style', 'container container-medium')
@section('content')

    <div class="row marketing wz-main-container-full">
        <div class="col-3">
            <nav class="nav flex-column">
                <a class="nav-link {{ $op == 'basic' ? 'active':'' }}" href="{{ wzRoute('project:setting:show', ['id' => $project->id, 'op' => 'basic']) }}">
                    <i class="icon-road mr-2"></i> @lang('project.basic')
                </a>
                <a class="nav-link {{ $op == 'privilege' ? 'active':'' }}" href="{{ wzRoute('project:setting:show', ['id' => $project->id, 'op' => 'privilege']) }}">
                    <i class="icon-sitemap mr-2"></i> @lang('project.privilege')
                </a>
                <a class="nav-link {{ $op == 'advanced' ? 'active':'' }}" href="{{ wzRoute('project:setting:show', ['id' => $project->id, 'op' => 'advanced']) }}">
                    <i class="icon-wrench mr-2"></i> @lang('project.advanced')
                </a>
            </nav>
        </div>
        <div class="col-9">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ wzRoute('user:home') }}">@lang('common.home')</a></li>
                @if(!empty($project->catalog))
                    <li class="breadcrumb-item"><a href="{{ wzRoute('home', ['catalog' => $project->catalog->id]) }}">{{ $project->catalog->name }}</a></li>
                @endif
                <li class="breadcrumb-item"><a href="{{ wzRoute('project:home', ['id' => $project->id]) }}">{{ $project->name }}</a></li>
                <li class="breadcrumb-item active">@lang("project.{$op}")</li>
            </ol>
            @include('components.error', ['error' => $errors ?? null])
            @yield('project-setting')
        </div>
    </div>

@endsection
