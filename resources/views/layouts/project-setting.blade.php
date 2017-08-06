@extends('layouts.default')

@section('container-style', 'container container-small')
@section('content')
    @include('layouts.navbar')

    <div class="row marketing">
        <div class="col-lg-12">
            <div class="col-lg-3">
                <ul class="nav nav-pills nav-stacked">
                    <li class="{{ $op == 'basic' ? 'active':'' }}"><a href="{{ wzRoute('project:setting:show', ['id' => $project->id, 'op' => 'basic']) }}">@lang('project.basic_info')</a></li>
                    <li class="{{ $op == 'privilege' ? 'active':'' }}"><a href="{{ wzRoute('project:setting:show', ['id' => $project->id, 'op' => 'privilege']) }}">项目权限</a></li>
                </ul>
            </div>
            <div class="col-lg-9">
                @include('components.error', ['error' => $errors ?? null])
                @yield('project-setting')
            </div>
        </div>
    </div>

@endsection
