@extends('layouts.admin')

@section('title', '用户组')
@section('breadcrumb')
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ wzRoute('user:home') }}">@lang('common.home')</a></li>
        <li class="breadcrumb-item">系统管理</li>
        <li class="breadcrumb-item"><a href="{{ wzRoute('admin:groups') }}">用户组管理</a></li>
        <li class="breadcrumb-item active">{{ $group->name }}</li>
    </ol>
@endsection
@section('admin-content')
    <ul class="nav nav-tabs" id="myTab" role="tablist">
        <li class="nav-item">
            <a class="nav-link active" id="member-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">成员</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="project-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false">项目</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="setting-tab" data-toggle="tab" href="#contact" role="tab" aria-controls="contact" aria-selected="false">设置</a>
        </li>
    </ul>
    <div class="tab-content" id="myTabContent">
        <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
            <div class="card card-white">
                <div class="card-header">添加成员</div>
                <div class="card-body">
                    <form method="post"
                          action="{!! wzRoute('admin:groups:users:add', ['id' => $group->id]) !!}">
                        {{ csrf_field() }}
                        <div class="form-group">
                            <select name="users[]" style="width: 440px;" class="form-control select2-multiple" id="wz-user-select" multiple>
                                @foreach($users_for_select as $user)
                                    <option value="{{ $user->id }}" data-name="{{ $user->name }}" data-email="{{ $user->email }}">
                                        {{ $user->name }}（{{ $user->email }}）
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary btn-raised">添加</button>
                            <a href="{!! wzRoute('admin:groups') !!}" class="btn btn-default">@lang('common.btn_back')</a>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card mt-3 card-white">
                <div class="card-header">成员</div>
                <table class="table">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>真实姓名</th>
                        <th>账号</th>
                        <th>注册时间</th>
                        <th>操作</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($users as $user)
                        <tr>
                            <th scope="row">{{ $user->id }}</th>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->created_at->format('Y-m-d H:i') }}</td>
                            <td>
                                <a href="#" wz-form-submit data-form="#form-user-{{ $user->id }}"
                                   data-confirm="确定要将用户从该用户组移除？">
                                    <i class="material-icons text-danger" title="@lang('common.btn_delete')">delete_sweep</i>
                                    <form id="form-user-{{ $user->id }}" method="post"
                                          action="{!! wzRoute('admin:groups:users:del', ['id' => $group->id, 'user_id' => $user->id]) !!}">
                                        {{ method_field('DELETE') }}{{ csrf_field() }}
                                    </form>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center">没有符合条件的信息！</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
            <div class="card card-white">
                <div class="card-header">添加项目</div>
                <div class="card-body">
                    <form method="post"
                          action="{!! wzRoute('admin:groups:projects:add', ['id' => $group->id]) !!}">
                        {{ csrf_field() }}

                        <div class="alert alert-info" role="alert">
                            以“<b>#</b>”开头的选项为项目目录，选择该项会将该目录下所有项目权限批量赋予用户组。
                        </div>
                        <div class="form-group">
                            <select name="projects[]" style="width: 440px;" class="form-control select2-multiple" id="wz-project-select" multiple>
                                @foreach($catalogs as $cat)
                                    <option value="#{{ $cat->id }}" data-name="#{{ $cat->name }}">#{{ $cat->name }}</option>
                                @endforeach
                                @foreach($projects_for_select as $proj)
                                    @php
                                        $projectName = $proj->name . (empty($proj->catalog_id) ? '' : "（#{$proj->catalog->name}）");
                                    @endphp
                                    <option value="{{ $proj->id }}" data-name="{{ $projectName }}">
                                        {{ $projectName }}
                                    </option>
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
                            <button type="submit" class="btn btn-primary btn-raised">添加</button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card mt-3 card-white">
                <div class="card-header">@lang('common.project')</div>
                <table class="table">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>@lang('project.project_name')</th>
                        <th>@lang('project.group_write_enabled')</th>
                        <th>@lang('common.operation')</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($projects as $project)
                        <tr>
                            <th scope="row">{{ $project->id }}</th>
                            <td>
                                <a href="{!! wzRoute('project:home', ['id' => $project->id]) !!}">{{ $project->name }}</a>
                                @if(!empty($project->catalog_id))
                                    <a target="_blank" class="badge badge-pill badge-info" href="{{ wzRoute('home', ['catalog' => $project->catalog_id]) }}">#{{ $project->catalog->name ?? '' }}</a>
                                @endif
                            </td>
                            <td>{{ $project->pivot->privilege == 1 ? __('common.yes') : __('common.no') }}</td>
                            <td>
                                <a href="#" wz-form-submit data-form="#form-project-{{ $project->id }}"
                                   data-confirm="确定要移除对该用户组对项目的访问权限吗？">
                                    <i class="material-icons text-danger" title="@lang('common.btn_delete')">delete_sweep</i>
                                    <form id="form-project-{{ $project->id }}" method="post"
                                          action="{!! wzRoute('project:privilege:revoke', ['id' => $project->id, 'group_id' => $group->id, 'redirect' => wzRoute('admin:groups:view', ['id' => $group->id])]) !!}">
                                        {{ method_field('DELETE') }}{{ csrf_field() }}
                                    </form>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center">没有符合条件的信息！</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="tab-pane fade" id="contact" role="tabpanel" aria-labelledby="contact-tab">
            <div class="card card-white">
                <div class="card-header">修改组名</div>
                <div class="card-body">
                    <form method="post"
                          action="{!! wzRoute('admin:groups:update', ['id' => $group->id]) !!}">
                        {{ csrf_field() }}
                        <div class="form-group">
                            <label for="form-name" class="bmd-label-floating">用户组名称</label>
                            <input id="form-name" type="text" name="name" class="form-control" value="{{ old('name', $group->name) }}" />
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-raised btn-primary" >保存</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>







@endsection

@push('stylesheet')
    <link href="{{ cdn_resource('/assets/vendor/select2/css/select2.min.css') }}" rel="stylesheet">
    <link href="{{ cdn_resource('/assets/vendor/select2-bootstrap-theme/select2-bootstrap.min.css') }}" rel="stylesheet">
@endpush

@push('script')
<script src="{{ cdn_resource('/assets/vendor/select2/js/select2.full.min.js') }}"></script>
<script>
    $(function () {
        $.fn.select2.defaults.set("theme", "bootstrap");

        $('#wz-user-select').select2({
            placeholder: '选择用户',
            templateSelection: function (data, container) {
                return $(data.element).data('name');
            }
        });

        $('#wz-project-select').select2({
            placeholder: '选择项目',
            templateSelection: function (data, container) {
                return $(data.element).data('name');
            }
        });

        $('#{{ $tab ?? 'member' }}-tab').trigger('click');
    });
</script>
@endpush