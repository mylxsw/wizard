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

    <ul class="nav nav-tabs" id="myTab" role="tablist">
        <li class="nav-item">
            <a class="nav-link active" id="basic-tab" data-toggle="tab" href="#basic" role="tab" aria-controls="basic" aria-selected="true">基本信息</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="user-group-tab" data-toggle="tab" href="#user-group" role="tab" aria-controls="user-group" aria-selected="false">用户组</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="project-tab" data-toggle="tab" href="#project" role="tab" aria-controls="project" aria-selected="false">项目</a>
        </li>
    </ul>
    <div class="tab-content" id="myTabContent">
        <div class="tab-pane fade show active" id="basic" role="tabpanel" aria-labelledby="basic-tab">
            <div class="card card-white">
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
                                   name="username" {{ ($user->id == Auth::user()->id || ldap_enabled()) ? 'readonly':'' }}>
                        </div>

                        @if(ldap_enabled())
                            <div class="form-group">
                                <label for="editor-objectguid" class="bmd-label-floating">LDAP 对象ID</label>
                                <input type="text" class="form-control" value="{{ $user->objectguid }}" id="editor-objectguid"
                                       name="objectguid" readonly>
                            </div>
                        @endif

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
        </div>
        <div class="tab-pane fade" id="user-group" role="tabpanel" aria-labelledby="user-group-tab">

            <div class="card card-white">
                <div class="card-header">加入用户组</div>
                <div class="card-body">
                    <form method="post"
                          action="{!! wzRoute('admin:user:join-group', ['id' => $user->id]) !!}">
                        {{ csrf_field() }}
                        <div class="form-group">
                            <select name="groups[]" style="width: 440px;" class="form-control select2-multiple" id="wz-group-select" multiple>
                                @foreach($group_for_select as $group)
                                    <option value="{{ $group->id }}" data-name="{{ $group->name }}">
                                        {{ $group->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary btn-raised">加入</button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card mt-3 card-white">
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
                                      action="{!! wzRoute('admin:groups:users:del', ['id' => $group->id, 'user_id' => $user->id, 'origin' => 'admin:user', 'origin_id' => $user->id,  'origin_tab' => 'user-group']) !!}">
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
        </div>
        <div class="tab-pane fade" id="project" role="tabpanel" aria-labelledby="project-tab">
            <div class="card mt-3 card-white">
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
                                <a href="{!! wzRoute('project:home', ['id' => $proj->id]) !!}">{{ $proj->name }}</a>
                                @if(!empty($proj->catalog_id))
                                    <a target="_blank" class="badge badge-pill badge-info" href="{{ wzRoute('home', ['catalog' => $proj->catalog_id]) }}">#{{ $proj->catalog->name ?? '' }}</a>
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
        // 鼠标经过提示
        $('[data-toggle="tooltip"]').tooltip({
            delay: { "show": 500, "hide": 100 }
        });

        $.fn.select2.defaults.set("theme", "bootstrap");

        $('#wz-group-select').select2({
            placeholder: '选择组',
            templateSelection: function (data, container) {
                return $(data.element).data('name');
            }
        });

        $('#{{ $tab ?? 'basic' }}-tab').trigger('click');
    });
</script>
@endpush