@extends('layouts.default')

@section('container-style', 'container container-small')
@section('content')
    @include('layouts.navbar')

    <div class="row marketing">
        <div class="col-lg-12">
            <div class="col-lg-3">
                <ul class="nav nav-pills nav-stacked">
                    <li class="{{ $op == 'basic' ? 'active':'' }}"><a href="{{ wzRoute('user:basic') }}">@lang('common.user_info')</a></li>
                    <li class="{{ $op == 'password' ? 'active':'' }}"><a href="{{ wzRoute('user:password') }}">@lang('common.change_password')</a></li>
                    <li class="{{ $op == 'notification' ? 'active':'' }}">
                        <a href="{{ wzRoute('user:notifications') }}">
                            通知
                            @if(userHasNotifications())
                                <span class="badge">{{ userNotificationCount() }}</span>
                            @endif
                        </a>
                    </li>
                    <li class="{{ $op == 'templates' ? 'active':'' }}">
                        <a href="{{ wzRoute('user:templates') }}">
                            模板管理
                        </a>
                    </li>
                </ul>
            </div>
            <div class="col-lg-9">
                @include('components.error', ['error' => $errors ?? null])
                @yield('user-content')
            </div>
        </div>
    </div>

@endsection
