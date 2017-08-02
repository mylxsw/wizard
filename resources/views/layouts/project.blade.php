@extends('layouts.default')
@section('container-style', 'container')
@section('content')
    @include('layouts.navbar')

    <div class="row marketing">
        <div class="col-lg-12">
            <div class="col-lg-3">

                <ul class="nav nav-pills nav-stacked wz-left-nav">
                    <li class="{{ $pageID === 0 ? 'active' : '' }}">
                        <a href="{{ wzRoute('project:home', ['id' => $project->id]) }}" class="wz-nav-item">
                            <span class="glyphicon glyphicon-th-large"></span>
                            {{ $project->name }}
                            @if($project->visibility == \App\Repositories\Project::VISIBILITY_PRIVATE)
                                <span title="私有项目" class="pull-right wz-box-tag glyphicon glyphicon-eye-close"></span>
                            @endif
                        </a>
                    </li>
                    @include('components.navbar', ['navbars' => $navigators])
                </ul>
            </div>
            <div class="col-lg-9">

                <nav class="wz-page-control clearfix">
                    @can('page-add', $project)
                        <div class="btn-group wz-nav-control">
                            <div class="btn-group" role="group">
                                <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown"
                                        aria-haspopup="true" aria-expanded="false">
                                    <span class="glyphicon glyphicon glyphicon-plus" aria-hidden="true"></span>
                                    新增 <span class="caret"></span>
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a href="{{ wzRoute('project:doc:new:show', ['id' => $project->id]) }}">文档</a>
                                    </li>
                                    <li><a href="{{ wzRoute('project:doc:new:show', ['id' => $project->id, 'type' => 'swagger']) }}">Swagger API</a></li>
                                </ul>
                            </div>
                            @can('project-setting', $project)
                                <a class="btn btn-default"
                                   href="{{ wzRoute('project:setting:show', ['id' => $project->id]) }}">项目配置</a>
                            @endcan
                        </div>
                    @endcan
                    @yield('project-control')
                </nav>
                <div class="panel panel-default">
                    <div class="panel-body">
                        @yield('page-content')
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
