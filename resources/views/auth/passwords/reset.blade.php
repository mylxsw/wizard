@extends('layouts.login')
@section('title', __('common.change_password'))
@section('content')
    <form class="form-signin" method="POST" action="{{ wzRoute('password.request') }}">
        {{ csrf_field() }}
        <input type="hidden" name="token" value="{{ $token }}">
        {{--<img class="mb-4" src="/assets/wizard.svg" alt="" height="100">--}}
        <h1 class="h3 mb-3 font-weight-normal">重置密码</h1>

        @if (session('status'))
            <div class="alert alert-success">
                {{ session('status') }}
            </div>
        @endif

        <div class="text-left form-group{{ $errors->has('email') ? ' has-error' : '' }}">
            <label for="email" class="bmd-label-floating">邮箱地址</label>
            <input id="email" type="email" class="form-control" name="email" value="{{ $email ?? old('email') }}" required autofocus>

            @if ($errors->has('email'))
                <div class="invalid-feedback d-block">{{ $errors->first('email') }}</div>
            @endif
        </div>

        <div class="text-left form-group{{ $errors->has('password') ? ' has-error' : '' }}">
            <label for="password" class="bmd-label-floating">密码</label>
            <input id="password" type="password" class="form-control" name="password" required>

            @if ($errors->has('password'))
                <div class="invalid-feedback d-block">
                    {{ $errors->first('password') }}
                </div>
            @endif
        </div>

        <div class="text-left form-group{{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
            <label for="password-confirm" class="bmd-label-floating">重复密码</label>
            <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required>

            @if ($errors->has('password_confirmation'))
                <div class="invalid-feedback d-block">
                    {{ $errors->first('password_confirmation') }}
                </div>
            @endif
        </div>


        <button type="submit" class="btn btn-lg btn-primary btn-block btn-raised">
            重置密码
        </button>
        <a href="{{ wzRoute('login') }}" class="btn btn-link">返回登录页</a>
    </form>
@endsection
