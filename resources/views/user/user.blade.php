@extends('layouts.admin')

@section('title', '用户管理')
@section('breadcrumb')
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ wzRoute('user:home') }}">@lang('common.home')</a></li>
        <li class="breadcrumb-item">系统管理</li>
        <li class="breadcrumb-item"><a href="{{ wzRoute('admin:users') }}">用户管理</a></li>
        <li class="breadcrumb-item active">{{ $user->name }}</li>
    </ol>
@endsection
@section('admin-content')
    <div class="card">
        <div class="card-header">编辑用户信息</div>
        <div class="card-body">
            @if($user->id == Auth::user()->id)
                <div class="alert alert-warning" role="alert">
                    目前系统不支持在 “用户管理” 功能下修改当前登录用户的信息。
                </div>
            @endif

            <form class="form-horizontal" method="post" action="{{ wzRoute('admin:user:update', ['id' => $user->id])  }}" style="max-width: 300px;">
                {{ csrf_field() }}
                <div class="form-group">
                    <label for="editor-email" class="bmd-label-floating">@lang('common.email')</label>
                    <input type="text" class="form-control" value="{{ $user->email }}" id="editor-email"
                           name="email" readonly>
                </div>
                <div class="form-group">
                    <label for="editor-username" class="bmd-label-floating">@lang('common.username')</label>
                    <input type="text" class="form-control" value="{{ $user->name }}" id="editor-username"
                           name="username" {{ $user->id == Auth::user()->id ? 'readonly':'' }}>
                </div>

                <div class="form-group">
                    <label class="bmd-label-floating">角色</label>
                    <div class="radio mt-2">
                        <label class="radio-inline">
                            <input type="radio" name="role" value="1" {{ $user->role == 1 ? 'checked':'' }} {{ $user->id == 1 ? 'disabled': '' }}> 普通用户
                        </label>
                        <label class="radio-inline">
                            <input type="radio" name="role" value="2" {{ $user->role == 2 ? 'checked':'' }} {{ $user->id == 1 ? 'disabled': '' }}> 管理员
                        </label>

                        @if($user->id == 1)
                            <i class="fa fa-question-circle" data-toggle="tooltip" title="该用户为系统初始管理员，不允许修改其身份"></i>
                        @endif
                    </div>
                </div>

                <div class="form-group">
                    <label for="editor-status" class="bmd-label-floating">状态</label>
                    <select id="editor-status" name="status" class="form-control"  {{ $user->id == Auth::user()->id ? 'disabled':'' }}>
                        <option value="0" {{ $user->status == 0 ? 'selected':'' }}>未激活</option>
                        <option value="1" {{ $user->status == 1 ? 'selected':'' }}>已激活</option>
                        <option value="2" {{ $user->status == 2 ? 'selected':'' }}>已禁用</option>
                    </select>
                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-success btn-raised"  {{ $user->id == Auth::user()->id ? 'disabled':'' }}>保存</button>
                    <a class="btn btn-default" href="{{ wzRoute('admin:users') }}">返回</a>
                </div>
            </form>
        </div>
    </div>
    <div class="card mt-3">
        <div class="card-header">已加入的用户组</div>
        <table class="table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>用户组名称</th>
                    <th>项目数</th>
                    <th>操作</th>
                </tr>
            </thead>
            <tbody>
                @forelse($user->groups()->withCount('projects')->get() as $group)
                    <tr>
                        <td>{{ $group->id }}</td>
                        <td>{{ $group->name }}</td>
                        <td>{{ $group->projects_count }}</td>
                        <td>
                            <form id="form-user-{{ $user->id }}" method="post"
                                  action="{!! wzRoute('admin:groups:users:del', ['id' => $group->id, 'user_id' => $user->id]) !!}">
                                {{ method_field('DELETE') }}{{ csrf_field() }}
                            </form>
                            <a href="#" wz-form-submit data-form="#form-user-{{ $user->id }}"
                               data-confirm="确定要将用户从该用户组移除？">
                                <i class="material-icons text-danger" title="解除">remove_circle_outline</i>
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

    <div class="card mt-3">
        <div class="card-header">创建的项目</div>
        <table class="table">
            <thead>
            <tr>
                <th>#</th>
                <th>项目名称</th>
                <th>文档数</th>
                <th>创建时间</th>
            </tr>
            </thead>
            <tbody>
            @forelse($user->projects()->with('catalog')->withCount('pages')->get() as $proj)
                <tr>
                    <td>{{ $proj->id }}</td>
                    <td>
                        <a href="{!! route('project:home', ['id' => $proj->id]) !!}">{{ $proj->name }}</a>
                        @if(!empty($proj->catalog_id))
                            <a target="_blank" class="badge badge-pill badge-info" href="{{ route('home', ['catalog' => $proj->catalog_id]) }}">#{{ $proj->catalog->name ?? '' }}</a>
                        @endif
                    </td>
                    <td>{{ $proj->pages_count }}</td>
                    <td>{{ $proj->created_at }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="3" class="text-center">没有符合条件的信息！</td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>
@endsection

@push('script')
<script>
    $(function () {
        // 鼠标经过提示
        $('[data-toggle="tooltip"]').tooltip({
            delay: { "show": 500, "hide": 100 }
        });
    });
</script>
@endpush