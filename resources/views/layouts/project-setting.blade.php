@extends('layouts.default')

@section('title', $project->name)
@section('container-style', 'container container-small')
@section('content')
    @include('layouts.navbar')

    <div class="row marketing">
        <div class="col-lg-12">
            <div class="col-lg-3">
                <ul class="nav nav-pills nav-stacked">
                    <li class="{{ $op == 'basic' ? 'active':'' }}"><a href="{{ wzRoute('project:setting:show', ['id' => $project->id, 'op' => 'basic']) }}">@lang('project.basic')</a></li>
                    <li class="{{ $op == 'privilege' ? 'active':'' }}"><a href="{{ wzRoute('project:setting:show', ['id' => $project->id, 'op' => 'privilege']) }}">@lang('project.privilege')</a></li>
                    <li class="{{ $op == 'advanced' ? 'active':'' }}"><a href="{{ wzRoute('project:setting:show', ['id' => $project->id, 'op' => 'advanced']) }}">@lang('project.advanced')</a></li>
                </ul>
            </div>
            <div class="col-lg-9">
                <ol class="breadcrumb">
                    <li><a href="{{ wzRoute('user:home') }}">@lang('common.home')</a></li>
                    <li><a href="{{ wzRoute('project:home', ['id' => $project->id]) }}">{{ $project->name }}</a></li>
                    <li class="active">@lang("project.{$op}")</li>
                </ol>
                @include('components.error', ['error' => $errors ?? null])
                @yield('project-setting')
            </div>
        </div>
    </div>

@endsection
