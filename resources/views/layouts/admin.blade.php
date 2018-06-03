@extends('layouts.default')

@section('container-style', 'container')
@section('content')

    <div class="row marketing wz-main-container-full">
        <div class="col-3">
            <nav class="nav flex-column">
                <a class="nav-link {{ $op == 'groups' ? 'active':'' }}" href="{{ wzRoute('admin:groups') }}">
                    <i class="fa fa-group mr-2"></i>用户组管理
                </a>
                <a class="nav-link {{ $op == 'users' ? 'active':'' }}" href="{!! wzRoute('admin:users') !!}">
                    <i class="fa fa-user mr-2"></i> 用户管理
                </a>
                <a class="nav-link {{ $op == 'catalogs' ? 'active':'' }}" href="{!! wzRoute('admin:catalogs') !!}">
                    <i class="fa fa-folder-open"></i> 项目目录管理
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
