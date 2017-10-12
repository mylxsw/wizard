@extends('layouts.default')

@section('container-style', 'container container-small')
@section('content')

    <div class="row marketing wz-main-container-full">
        <div class="col-lg-12">
            <div class="col-lg-3">
                <ul class="nav nav-pills nav-stacked">
                    <li class="{{ $op == 'groups' ? 'active':'' }}"><a href="{{ wzRoute('admin:groups') }}">用户组管理</a></li>
                    <li class="{{ $op == 'users' ? 'active':'' }}"><a href="{!! wzRoute('admin:users') !!}">用户管理</a></li>
                </ul>
            </div>
            <div class="col-lg-9">
                @yield('breadcrumb')
                @include('components.error', ['error' => $errors ?? null])
                @yield('admin-content')
            </div>
        </div>
    </div>

@endsection
