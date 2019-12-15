@extends('layouts.admin')

@section('title', '用户组')
@section('breadcrumb')
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ wzRoute('user:home') }}">@lang('common.home')</a></li>
        <li class="breadcrumb-item">系统管理</li>
        <li class="breadcrumb-item active">用户组管理</li>
    </ol>
@endsection
@section('admin-content')
    <div class="card card-white">
        <div class="card-header">创建用户组</div>
        <div class="card-body">
            <form method="post"
                  action="{!! wzRoute('admin:groups:add') !!}">
                {{ csrf_field() }}
                <div class="form-group">
                    <label for="form-name" class="bmd-label-floating">用户组名称</label>
                    <input id="form-name" type="text" name="name" class="form-control" value="{{ old('name') }}" />
                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-raised btn-primary" >创建用户组</button>
                </div>
            </form>
        </div>
    </div>

    <div class="card mt-3 card-white">
        <div class="card-header">用户组</div>
        <table class="table">
            <thead>
            <tr>
                <th>#</th>
                <th>用户组名称</th>
                <th>用户数</th>
                <th>项目数</th>
                <th>操作</th>
            </tr>
            </thead>
            <tbody>
            @forelse($groups as $group)
                <tr>
                    <th scope="row">{{ $group->id }}</th>
                    <td><a href="{!! wzRoute('admin:groups:view', ['id' => $group->id]) !!}">{{ $group->name }}</a></td>
                    <td>{{ $group->users_count }}</td>
                    <td>{{ $group->projects_count }}</td>
                    <td>
                        <a href="{!! wzRoute('admin:groups:view', ['id' => $group->id]) !!}">
                            <i class="material-icons" title="管理">create</i>
                        </a>
                        &nbsp;&nbsp;
                        <a href="#" wz-form-submit data-form="#form-group-{{ $group->id }}"
                           data-confirm="确定要删除该分组？将会同步删除该分组分配的项目权限和用户关系">
                            <i class="material-icons text-danger" title="@lang('common.btn_delete')">delete_sweep</i>
                            <form id="form-group-{{ $group->id }}" method="post"
                                  action="{{ wzRoute('admin:groups:del', ['id' => $group->id]) }}">
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
@endsection
