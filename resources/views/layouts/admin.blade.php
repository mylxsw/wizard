@extends('layouts.default')

@section('container-style', 'container')
@section('content')

    <div class="row marketing wz-main-container-full">
        <div class="col-3">
            <nav class="nav flex-column">
                <a class="nav-link {{ $op == 'groups' ? 'active':'' }}" href="{{ wzRoute('admin:groups') }}">
                    <i class="icon-group mr-2"></i>用户组管理
                </a>
                <a class="nav-link {{ $op == 'users' ? 'active':'' }}" href="{!! wzRoute('admin:users') !!}">
                    <i class="icon-user mr-2"></i> 用户管理
                </a>
            </nav>
        </div>
        <div class="col-9">
            @yield('breadcrumb')
            @include('components.error', ['error' => $errors ?? null])
            @yield('admin-content')
        </div>
    </div>

@endsection
