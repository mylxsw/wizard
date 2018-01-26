@extends('layouts.default')

@section('title', '个人首页')
@section('container-style', 'container container-small')
@section('content')

    <div class="card mt-4">
        <div class="card-header">我的项目</div>
        <div class="card-body">
            <div class="row marketing wz-main-container-full col-12">
                @foreach($projects ?? [] as $proj)
                    <div class="col-3">
                        <a class="wz-box" href="{{ wzRoute('project:home', ['id'=> $proj->id]) }}" >
                            @if($proj->visibility == \App\Repositories\Project::VISIBILITY_PRIVATE)
                                <span title="@lang('project.privilege_private')" class="wz-box-tag glyphicon glyphicon-eye-close"></span>
                            @endif
                            <p class="wz-title" title="{{ $proj->name }}">{{ $proj->name }}</p>
                        </a>
                    </div>
                @endforeach
                @can('project-create')
                    <div class="col-3">
                        <a class="wz-box wz-box-new" href="#"
                           data-toggle="modal" data-target="#wz-new-project">
                            <p class="wz-title"><span class="icon-plus"></span> @lang('project.new_project')</p>
                        </a>
                    </div>
                @else
                    <div class="col-3">
                <span class="wz-box-disabled">
                    <p class="wz-title" title="您没有创建项目的权限">
                        <span class="icon-ban-circle"></span>
                        @lang('project.new_project')
                    </p>
                </span>
                    </div>
                @endcan
            </div>
            <div class="row">
                <div class="wz-pagination">
                    {{ $projects->links() }}
                </div>
            </div>
        </div>
    </div>

    @can('project-create')
        <div class="modal fade" id="wz-new-project" tabindex="-1" role="dialog" aria-labelledby="wz-new-project">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">@lang('project.new_project')</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form method="post" action="{{ wzRoute('project:new:handle') }}" id="wz-project-save-form">
                            {{ csrf_field() }}
                            <div class="form-group">
                                <label for="project-name" class="bmd-label-floating">@lang('project.project_name')：</label>
                                <input type="text" name="name" placeholder="@lang('project.project_name')" class="form-control" id="project-name">
                            </div>
                            <div class="form-group">
                                <label for="project-description" class="bmd-label-floating">@lang('project.description')：</label>
                                <textarea class="form-control" name="description" placeholder="@lang('project.description')" id="project-description"></textarea>
                            </div>
                            <div class="form-group">
                                <label for="project-visibility" class="control-label">@lang('project.privilege')：</label>
                                <div class="radio mt-2">
                                    <label class="radio-inline">
                                        <input type="radio" name="visibility" id="project-visibility" value="{{ \App\Repositories\Project::VISIBILITY_PUBLIC }}" checked>
                                        @lang('project.privilege_public')
                                    </label>
                                    <label class="radio-inline">
                                        <input type="radio" name="visibility" value="{{ \App\Repositories\Project::VISIBILITY_PRIVATE }}">
                                        @lang('project.privilege_private')
                                    </label>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary btn-raised mr-2" id="wz-project-save">@lang('common.btn_save')</button>
                        <button type="button" class="btn btn-default" data-dismiss="modal">@lang('common.btn_close')</button>
                    </div>
                </div>
            </div>
        </div>
    @endcan

@endsection

@push('script')
<script>
    $(function() {
        $('#wz-project-save').on('click', function () {
            var form = $('#wz-project-save-form');

            $.wz.btnAutoLock($(this));

            $.wz.asyncForm(form, {}, function (data) {
                window.location.reload(true);
            });
        });
    });
</script>
@endpush