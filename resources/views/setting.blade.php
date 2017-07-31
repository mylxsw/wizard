@extends('layouts.default')

@section('container-style', 'container container-small')
@section('content')
    @include('layouts.navbar')

    <div class="row marketing">
        <div class="col-lg-12">
            <div class="col-lg-3">
                <ul class="nav nav-pills nav-stacked">
                    <li class="active"><a href="{{ wzRoute('project:setting:show', ['id' => $project->id]) }}">基本信息</a></li>
                </ul>
            </div>
            <div class="col-lg-9">
                @include('components.error', ['error' => $errors ?? null])
                <form class="form-horizontal" method="post" action="{{ wzRoute('project:setting:handle', ['id' => $project->id]) }}">
                    {{ csrf_field() }}
                    <input type="hidden" name="project_id" value="{{ $project->id }}">
                    <div class="form-group">
                        <label for="editor-project-name" class="col-sm-2 control-label">项目名称</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control"
                                   name="name" id="editor-project-name"
                                   value="{{ $project->name or '' }}" placeholder="项目名称">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="editor-description" class="col-sm-2 control-label">项目描述</label>
                        <div class="col-sm-10">
                            <textarea class="form-control" name="description" id="editor-description" rows="3">{{ $project->description or '' }}</textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="project-visibility" class="col-sm-2 control-label">项目权限</label>
                        <div class="col-sm-10">
                            <div class="radio-inline">
                                <label>
                                    <input type="radio" name="visibility" id="project-visibility" value="{{ \App\Repositories\Project::VISIBILITY_PUBLIC }}" {{ $project->visibility == \App\Repositories\Project::VISIBILITY_PUBLIC ? 'checked' : '' }}>
                                    公开
                                </label>
                            </div>
                            <div class="radio-inline">
                                <label>
                                    <input type="radio" name="visibility" value="{{ \App\Repositories\Project::VISIBILITY_PRIVATE }}" {{ $project->visibility == \App\Repositories\Project::VISIBILITY_PRIVATE ? 'checked' : '' }}>
                                    私有
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-10">
                            <button type="submit" class="btn btn-success">更新</button>
                            <a href="{{ wzRoute('project:home', ['id' => $project->id]) }}" class="btn btn-default">返回</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection
