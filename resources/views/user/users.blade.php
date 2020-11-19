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
    <div class="card card-white">
        <div class="card-header">搜索</div>
        <div class="card-body">
            <form method="get"
                  action="{!! wzRoute('admin:users') !!}">
                <div class="form-group">
                    <label for="form-name" class="bmd-label-floating">用户名</label>
                    <input id="form-name" type="text" name="name" class="form-control w-75" value="{{ $query['name'] ?? ''  }}" />
                </div>
                <div class="form-group">
                    <label for="search-email" class="bmd-label-floating">邮箱帐号</label>
                    <input type="email" name="email" class="form-control float-left w-75" id="search-email" value="{{ $query['email'] ?? '' }}" />
                </div>
                <br/>
                <div class="form-group">
                    <button type="submit" class="btn btn-raised btn-primary" >搜索</button>
                </div>
            </form>
        </div>
    </div>
    <div class="card mt-3 card-white">
        <table class="table">
            <thead>
            <tr>
                <th>#</th>
                <th>真实姓名</th>
                <th>账号{{ ldap_enabled() ? ' / LDAP':'' }}</th>
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
                    <td>
                        <a href="{{ wzRoute('admin:user', ['id' => $user->id]) }}">{{ $user->email }}</a>
                        @if(ldap_enabled())
                            <br/>
                            {{ $user->objectguid ?? '-' }}
                        @endif
                    </td>
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
                        @canBeImpersonated($user)
                        <a href="#" wz-form-submit data-form="#form-impersonate-{{ $user->id }}">
                            <i class="material-icons text-warning" title="扮演">transfer_within_a_station</i>
                            <form id="form-impersonate-{{ $user->id }}" method="post" style="display: none"
                                  action="{{ wzRoute('impersonate:start', ['id' => $user->id]) }}">
                                {{ method_field('POST') }}{{ csrf_field() }}
                            </form>
                        </a>
                        @endCanBeImpersonated
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
