@extends('layouts.admin')

@section('title', '用户管理')
@section('breadcrumb')
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ wzRoute('user:home') }}">@lang('common.home')</a></li>
        <li class="breadcrumb-item">系统管理</li>
        <li class="breadcrumb-item active">用户管理</li>
    </ol>
@endsection
@section('admin-content')

    <div class="card">
        <table class="table">
            <thead>
            <tr>
                <th>#</th>
                <th>真实姓名</th>
                <th>账号</th>
                <th>角色</th>
                <th>状态</th>
                <th>注册时间</th>
                <th>操作</th>
            </tr>
            </thead>
            <tbody>
            @forelse($users as $user)
                <tr class="{{ $user->id == Auth::user()->id ? 'info' : '' }}">
                    <th scope="row">{{ $user->id }}</th>
                    <td>{{ $user->name }}</td>
                    <td><a href="{{ wzRoute('admin:user', ['id' => $user->id]) }}">{{ $user->email }}</a></td>
                    <td>{{ $user->isAdmin() ? '管理员':'普通' }}</td>
                    <td>
                        @if($user->status == 0)
                            <span style="color: orangered;">未激活</span>
                        @elseif($user->status == 1)
                            <span style="color: green;">已激活</span>
                        @elseif($user->status == 2)
                            <span style="color: red;">已禁用</span>
                        @endif
                    </td>
                    <td>{{ $user->created_at->format('Y-m-d H:i') }}</td>
                    <td>
                        <a href="{{ wzRoute('admin:user', ['id' => $user->id]) }}">
                            <i class="material-icons" title="管理">create</i>
                        </a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center">没有符合条件的信息！</td>
                </tr>
            @endforelse
            </tbody>
        </table>

        <div class="wz-pagination">
            {{ $users->links() }}
        </div>
    </div>
@endsection
