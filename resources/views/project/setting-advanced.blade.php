@extends('layouts.project-setting')

@section('project-setting')
    <input type="hidden" name="project_id" value="{{ $project->id }}">
    <input type="hidden" name="op" value="{{ $op }}">

    @can('project-delete', $project)
        <div class="panel panel-default">
            <div class="panel-heading">@lang('project.project_delete')</div>
            <div class="panel-body">
                <form id="form-project-del" method="post"
                      action="{{ wzRoute('project:delete', ['id' => $project->id]) }}">
                    {{ method_field('DELETE') }}{{ csrf_field() }}
                </form>
                <div class="alert alert-warning" role="alert">@lang('project.delete_warning')</div>
                <a href="#" wz-form-submit data-form="#form-project-del"
                   data-confirm="@lang('project.delete_confirm', ['name' => $project->name])" class="btn btn-danger">@lang('project.project_delete')</a>
            </div>
        </div>
    @endcan
@endsection
