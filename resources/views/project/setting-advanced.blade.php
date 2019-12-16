@extends('layouts.project-setting')

@section('project-setting')
    <div class="card card-white">
        <div class="card-body">
            <input type="hidden" name="project_id" value="{{ $project->id }}">
            <input type="hidden" name="op" value="{{ $op }}">

            @can('project-delete', $project)
                <form id="form-project-del" method="post"
                      action="{{ wzRoute('project:delete', ['id' => $project->id]) }}">
                    {{ method_field('DELETE') }}{{ csrf_field() }}
                </form>
                <div class="alert alert-warning" role="alert">@lang('project.delete_warning')</div>
                <button wz-form-submit data-form="#form-project-del"
                        data-confirm="@lang('project.delete_confirm', ['name' => $project->name])" class="btn btn-danger btn-raised">@lang('project.project_delete')</button>
            @endcan
        </div>
    </div>
@endsection
