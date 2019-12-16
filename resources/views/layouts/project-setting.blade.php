@extends('layouts.default')

@section('title', $project->name)
@section('container-style', 'container container-medium')
@section('content')

    <div class="row marketing wz-main-container-full">
        <div class="col-12 wz-left-main-full">
            <a class="wz-left-main-switch btn"><i class="fa fa-angle-double-down"></i> </a>
        </div>
        <div class="col-12 col-lg-3 wz-left-main">
            <nav class="nav flex-column">
                <a class="nav-link {{ $op == 'basic' ? 'active':'' }}" href="{{ wzRoute('project:setting:show', ['id' => $project->id, 'op' => 'basic']) }}">
                    <i class="fa fa-road mr-2"></i> @lang('project.basic')
                </a>
                <a class="nav-link {{ $op == 'privilege' ? 'active':'' }}" href="{{ wzRoute('project:setting:show', ['id' => $project->id, 'op' => 'privilege']) }}">
                    <i class="fa fa-sitemap mr-2"></i> @lang('project.privilege')
                </a>
                <a class="nav-link {{ $op == 'sort' ? 'active':'' }}" href="{{ wzRoute('project:setting:show', ['id' => $project->id, 'op' => 'sort']) }}">
                    <i class="fa fa-sort-alpha-asc mr-2"></i> @lang('project.sort')
                </a>
                <a class="nav-link {{ $op == 'advanced' ? 'active':'' }}" href="{{ wzRoute('project:setting:show', ['id' => $project->id, 'op' => 'advanced']) }}">
                    <i class="fa fa-wrench mr-2"></i> @lang('project.advanced')
                </a>
            </nav>
        </div>
        <div class="col-12 col-lg-9">
            <div class="wz-setting-breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ wzRoute('user:home') }}">@lang('common.home')</a></li>
                    @if(!empty($project->catalog))
                        <li class="breadcrumb-item"><a href="{{ wzRoute('home', ['catalog' => $project->catalog->id]) }}">{{ $project->catalog->name }}</a></li>
                    @endif
                    <li class="breadcrumb-item"><a href="{{ wzRoute('project:home', ['id' => $project->id]) }}">{{ $project->name }}</a></li>
                    <li class="breadcrumb-item active">@lang("project.{$op}")</li>
                </ol>
            </div>
            @include('components.error', ['error' => $errors ?? null])
            @yield('project-setting')
        </div>
    </div>

@endsection
