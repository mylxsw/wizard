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

    <div class="card">
        <div class="card-header">新增成员</div>
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
                    <a href="{!! route('admin:groups') !!}" class="btn btn-default">@lang('common.btn_back')</a>
                </div>
            </form>
        </div>
    </div>

    <div class="card mt-3">
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
                            @lang('common.btn_delete')
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

    <div class="card mt-3">
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
                        <a href="{!! route('project:home', ['id' => $project->id]) !!}">{{ $project->name }}</a>
                        @if(!empty($project->catalog_id))
                            <a target="_blank" class="badge badge-pill badge-info" href="{{ route('home', ['catalog' => $project->catalog_id]) }}">#{{ $project->catalog->name ?? '' }}</a>
                        @endif
                    </td>
                    <td>{{ $project->pivot->privilege == 1 ? __('common.yes') : __('common.no') }}</td>
                    <td>
                        <a href="#" wz-form-submit data-form="#form-project-{{ $project->id }}"
                           data-confirm="确定要移除对该用户组对项目的访问权限吗？">
                            @lang('common.btn_delete')
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


@endsection

@push('stylesheet')
    <link href="/assets/vendor/select2/css/select2.min.css" rel="stylesheet">
    <link href="/assets/vendor/select2-bootstrap-theme/select2-bootstrap.min.css" rel="stylesheet">
@endpush

@push('script')
<script src="/assets/vendor/select2/js/select2.full.min.js"></script>
<script>
    $(function () {
        $.fn.select2.defaults.set("theme", "bootstrap");

        $('#wz-user-select').select2({
            placeholder: '选择用户',
            templateSelection: function (data, container) {
                return $(data.element).data('name');
            }
        });
    });
</script>
@endpush