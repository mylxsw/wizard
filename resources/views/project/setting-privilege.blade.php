@extends('layouts.project-setting')

@section('project-setting')
    <div class="card card-white">
        <div class="card-body">
            <form method="post" action="{{ wzRoute('project:setting:handle', ['id' => $project->id]) }}">
                {{ csrf_field() }}
                <input type="hidden" name="project_id" value="{{ $project->id }}">
                <input type="hidden" name="op" value="{{ $op }}">
                <div class="form-group">
                    <label for="editor-group-id" class="control-label">@lang('project.group_name')</label>
                    <select class="form-control" name="group_id" id="editor-group-id" style="min-width: 150px;">
                        @foreach($restGroups as $group)
                            <option value="{{ $group->id }}">{{ $group->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" name="privilege" value="wr"> @lang('project.group_write_enabled')
                        </label>
                    </div>
                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-success btn-raised" {{ empty($restGroups) ? 'disabled="disabled"' : '' }}>@lang('common.btn_add')</button>
                    <a href="{{ wzRoute('project:home', ['id' => $project->id]) }}" class="btn btn-default">@lang('common.btn_back')</a>
                </div>
            </form>
        </div>
    </div>

    <div class="card card-white mt-3">
        <div class="card-header">@lang('project.group_added')</div>
        <table class="table">
            <thead>
            <tr>
                <th>#</th>
                <th>@lang('project.group_name')</th>
                <th>@lang('project.group_write_enabled')</th>
                <th>@lang('common.operation')</th>
            </tr>
            </thead>
            <tbody>
            @foreach($addedGroups as $group)
                <tr>
                    <th scope="row">{{ $group->id }}</th>
                    <td>{{ $group->name }}</td>
                    <td>{{ $group->projects[0]->pivot->privilege == 1 ? __('common.yes') : __('common.no') }}</td>
                    <td>
                        <a href="#" wz-form-submit data-form="#form-group-{{ $group->id }}">
                            <i class="material-icons text-danger" title="@lang('common.btn_delete')">remove_circle_outline</i>
                            <form id="form-group-{{ $group->id }}" method="post"
                                  action="{{ wzRoute('project:privilege:revoke', ['id' => $project->id, 'group_id' => $group->id]) }}">
                                {{ method_field('DELETE') }}{{ csrf_field() }}
                            </form>
                        </a>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>

@endsection
