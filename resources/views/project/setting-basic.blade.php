@extends('layouts.project-setting')

@section('project-setting')
    <form class="form-horizontal" method="post" action="{{ wzRoute('project:setting:handle', ['id' => $project->id]) }}">
        {{ csrf_field() }}
        <input type="hidden" name="project_id" value="{{ $project->id }}">
        <input type="hidden" name="op" value="{{ $op }}">
        <div class="form-group">
            <label for="editor-project-name" class="col-sm-2 control-label">@lang('project.project_name')</label>
            <div class="col-sm-10">
                <input type="text" class="form-control"
                       name="name" id="editor-project-name"
                       value="{{ $project->name or '' }}" placeholder="@lang('project.project_name')">
            </div>
        </div>
        <div class="form-group">
            <label for="editor-description" class="col-sm-2 control-label">@lang('project.description')</label>
            <div class="col-sm-10">
                <textarea class="form-control" name="description" id="editor-description" rows="3">{{ $project->description or '' }}</textarea>
            </div>
        </div>
        <div class="form-group">
            <label for="project-visibility" class="col-sm-2 control-label">@lang('project.privilege')</label>
            <div class="col-sm-10">
                <div class="radio-inline">
                    <label>
                        <input type="radio" name="visibility" id="project-visibility" value="{{ \App\Repositories\Project::VISIBILITY_PUBLIC }}" {{ $project->visibility == \App\Repositories\Project::VISIBILITY_PUBLIC ? 'checked' : '' }}>
                        @lang('project.privilege_public')
                    </label>
                </div>
                <div class="radio-inline">
                    <label>
                        <input type="radio" name="visibility" value="{{ \App\Repositories\Project::VISIBILITY_PRIVATE }}" {{ $project->visibility == \App\Repositories\Project::VISIBILITY_PRIVATE ? 'checked' : '' }}>
                        @lang('project.privilege_private')
                    </label>
                </div>
            </div>
        </div>
        <div class="form-group">
            <div class="col-sm-offset-2 col-sm-10">
                <button type="submit" class="btn btn-success">@lang('common.btn_save')</button>
                <a href="{{ wzRoute('project:home', ['id' => $project->id]) }}" class="btn btn-default">@lang('common.btn_back')</a>
            </div>
        </div>
    </form>
@endsection
