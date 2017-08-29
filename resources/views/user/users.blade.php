@extends('layouts.admin')

@section('title', '用户管理')
@section('breadcrumb')
    <ol class="breadcrumb">
        <li><a href="{{ wzRoute('user:home') }}">@lang('common.home')</a></li>
        <li>系统管理</li>
        <li class="active">用户管理</li>
    </ol>
@endsection
@section('admin-content')

    <table class="table">
        <thead>
        <tr>
            <th>#</th>
            <th>真实姓名</th>
            <th>账号</th>
            <th>角色</th>
            <th>注册时间</th>
            <th>操作</th>
        </tr>
        </thead>
        <tbody>
        @forelse($users as $user)
            <tr class="{{ $user->id == Auth::user()->id ? 'info' : '' }}">
                <th scope="row">{{ $user->id }}</th>
                <td>{{ $user->name }}</td>
                <td>{{ $user->email }}</td>
                <td>{{ $user->isAdmin() ? '管理员':'普通' }}</td>
                <td>{{ $user->created_at->format('Y-m-d H:i') }}</td>
                <td>
                    <a href="#" wz-wait-develop>
                        设置
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

    <div class="wz-pagination">
        {{ $users->links() }}
    </div>
@endsection
