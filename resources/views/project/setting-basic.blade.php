@extends('layouts.project-setting')

@section('project-setting')
    <div class="card card-white">
        <div class="card-body">
            <form class="form-horizontal" method="post" action="{{ wzRoute('project:setting:handle', ['id' => $project->id]) }}">
                {{ csrf_field() }}
                <input type="hidden" name="project_id" value="{{ $project->id }}">
                <input type="hidden" name="op" value="{{ $op }}">
                <div class="form-group">
                    <label for="editor-project-name" class="bmd-label-floating">@lang('project.project_name')</label>
                    <input type="text" class="form-control"
                           name="name" id="editor-project-name"
                           value="{{ old('name', $project->name) }}" >
                </div>
                <div class="form-group">
                    <label for="catalog-status" class="bmd-label-floating">目录</label>
                    <select id="catalog-status" name="catalog" class="form-control">
                        <option value="0" {{ empty($project->catalog) ? 'selected' : '' }}>无</option>
                        @foreach($catalogs as $cat)
                            <option value="{{ $cat->id }}" {{ $cat->id == $project->catalog_id ? 'selected':'' }}>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="editor-description" class="bmd-label-floating">@lang('project.description')</label>
                    <textarea class="form-control" name="description" id="editor-description" rows="3">{{ old('description', $project->description) }}</textarea>
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
                    <label for="project-sort" class="bmd-label-floating">排序（值越大越靠后）</label>
                    <input type="number" name="sort_level" class="form-control float-left w-75" id="project-sort" value="{{ old('sort_level', $project->sort_level) }}" {{ Auth::user()->can('project-sort') ? '' : 'disabled' }}/>
                    <i class="fa fa-question-circle ml-2" data-toggle="tooltip" title="" data-original-title="只有管理员可以修改"></i>
                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-success btn-raised mr-2">@lang('common.btn_save')</button>
                    <a href="{{ wzRoute('project:home', ['id' => $project->id]) }}" class="btn btn-default">@lang('common.btn_back')</a>
                </div>
            </form>
        </div>
    </div>
@endsection
