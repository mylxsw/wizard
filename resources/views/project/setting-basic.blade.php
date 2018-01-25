@extends('layouts.project-setting')

@section('project-setting')
    <div class="card">
        <div class="card-body">
            <form class="form-horizontal" method="post" action="{{ wzRoute('project:setting:handle', ['id' => $project->id]) }}">
                {{ csrf_field() }}
                <input type="hidden" name="project_id" value="{{ $project->id }}">
                <input type="hidden" name="op" value="{{ $op }}">
                <div class="form-group">
                    <label for="editor-project-name" class="bmd-label-floating">@lang('project.project_name')</label>
                    <input type="text" class="form-control"
                           name="name" id="editor-project-name"
                           value="{{ $project->name or '' }}" >
                </div>
                <div class="form-group">
                    <label for="editor-description" class="bmd-label-floating">@lang('project.description')</label>
                    <textarea class="form-control" name="description" id="editor-description" rows="3">{{ $project->description or '' }}</textarea>
                </div>
                <div class="form-group">
                    <label for="project-visibility" class="bmd-label-floating">@lang('project.privilege')</label>
                    <div class="radio mt-2">
                        <label class="radio-inline">
                            <input type="radio" name="visibility" id="project-visibility" value="{{ \App\Repositories\Project::VISIBILITY_PUBLIC }}" {{ $project->visibility == \App\Repositories\Project::VISIBILITY_PUBLIC ? 'checked' : '' }}>
                            @lang('project.privilege_public')
                        </label>
                        <label class="radio-inline">
                            <input type="radio" name="visibility" value="{{ \App\Repositories\Project::VISIBILITY_PRIVATE }}" {{ $project->visibility == \App\Repositories\Project::VISIBILITY_PRIVATE ? 'checked' : '' }}>
                            @lang('project.privilege_private')
                        </label>
                    </div>
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-success btn-raised">@lang('common.btn_save')</button>
                    <a href="{{ wzRoute('project:home', ['id' => $project->id]) }}" class="btn btn-default">@lang('common.btn_back')</a>
                </div>
            </form>
        </div>
    </div>
@endsection
