@extends('layouts.default')

@section('container-style', 'container container-small')
@section('content')

    @include('layouts.navbar')

    <div class="row marketing">
        <div class="col-lg-12">
            @foreach($projects ?? [] as $proj)
                <div class="col-lg-3">
                    <a class="wz-box" href="{{ wzRoute('project:home', ['id'=> $proj->id]) }}"
                       title="{{ $proj->description }}">
                        @if($proj->visibility == \App\Repositories\Project::VISIBILITY_PRIVATE)
                            <span title="私有项目" class="wz-box-tag glyphicon glyphicon-eye-close"></span>
                        @endif
                        <p class="wz-title">{{ $proj->name }}</p>
                    </a>
                </div>
            @endforeach
            <div class="col-lg-3">
                <a class="wz-box" href="#"
                   data-toggle="modal" data-target="#wz-new-project">
                    <p class="wz-title"><span class="glyphicon glyphicon glyphicon-plus"></span> 新增项目</p>
                </a>
            </div>
        </div>
    </div>

    <div class="modal fade" id="wz-new-project" tabindex="-1" role="dialog" aria-labelledby="wz-new-project">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="exampleModalLabel">新增项目</h4>
                </div>
                <div class="modal-body">
                    <form method="post" action="{{ wzRoute('project:new:handle') }}" id="wz-project-save-form">
                        {{ csrf_field() }}
                        <div class="form-group">
                            <label for="project-name" class="control-label">项目名称：</label>
                            <input type="text" name="name" placeholder="项目名称" class="form-control" id="project-name">
                        </div>
                        <div class="form-group">
                            <label for="project-description" class="control-label">项目描述：</label>
                            <textarea class="form-control" name="description" placeholder="项目描述" id="project-description"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="project-visibility" class="control-label">项目权限：</label>
                            <div class="radio-inline">
                                <label>
                                    <input type="radio" name="visibility" id="project-visibility" value="{{ \App\Repositories\Project::VISIBILITY_PUBLIC }}" checked>
                                    公开
                                </label>
                            </div>
                            <div class="radio-inline">
                                <label>
                                    <input type="radio" name="visibility" value="{{ \App\Repositories\Project::VISIBILITY_PRIVATE }}">
                                    私有
                                </label>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-success" id="wz-project-save">保存</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('script')
<script>
    $(function() {
        $('#wz-project-save').on('click', function () {
            var form = $('#wz-project-save-form');

            $.wz.asyncForm(form, {}, function (data) {
                window.location.reload(true);
            });
        });
    });
</script>
@endpush