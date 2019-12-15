@extends('layouts.default')

@section('container-style', 'container container-medium')
@section('content')
    <div class="row marketing wz-main-container-full">
        <div class="col-12 wz-left-main-full">
            <a class="wz-left-main-switch btn"><i class="fa fa-angle-double-down"></i> </a>
        </div>
        <div class="col-12 col-lg-3 wz-left-main">
            <nav class="nav flex-column">
                <a class="nav-link {{ $op == 'basic' ? 'active':'' }}" href="{{ wzRoute('user:basic') }}">
                    <i class="fa fa-user mr-2"></i> @lang('common.user_info')
                </a>
                @if (!ldap_enabled())
                    <a class="nav-link {{ $op == 'password' ? 'active':'' }}" href="{{ wzRoute('user:password') }}">
                        <i class="fa fa-lock mr-2"></i> @lang('common.change_password')
                    </a>
                @endif
                <a class="nav-link {{ $op == 'notification' ? 'active':'' }}" href="{{ wzRoute('user:notifications') }}">
                    <i class="fa fa-bell mr-2"></i> 通知
                    @if(userHasNotifications())
                        <span class="badge">{{ userNotificationCount() }}</span>
                    @endif
                </a>
                <a class="nav-link {{ $op == 'templates' ? 'active':'' }}" href="{{ wzRoute('user:templates') }}">
                    <i class="fa fa-th mr-2"></i> 模板管理
                </a>
            </nav>
        </div>
        <div class="col-12 col-lg-9">
            <div class="wz-setting-breadcrumb">@yield('breadcrumb')</div>
            @include('components.error', ['error' => $errors ?? null])
            @yield('user-content')
        </div>
    </div>

@endsection
