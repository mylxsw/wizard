@extends('layouts.login')
@section('title', __('common.change_password'))
@section('content')

    <form class="form-signin" method="POST" action="{{ wzRoute('password.email') }}">
        {{ csrf_field() }}
        {{--<img class="mb-4" src="/assets/wizard.svg" alt="" height="100">--}}
        <h1 class="h3 mb-3 font-weight-normal">重置密码</h1>

        @if (session('status'))
            <div class="alert alert-success">
                {{ session('status') }}
            </div>
        @endif

        <div class="text-left form-group{{ $errors->has('email') ? ' has-error' : '' }}">
            <label for="email" class="bmd-label-floating">邮箱地址</label>
            <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required>

            @if ($errors->has('email'))
                <div class="invalid-feedback" style="display: block;">
                    {{ $errors->first('email') }}
                </div>
            @endif
        </div>

        <button type="submit" class="btn btn-lg btn-primary btn-block btn-raised">
            发送密码重置链接
        </button>
        <a href="{{ wzRoute('login') }}" class="btn btn-link">返回登录页</a>
    </form>
@endsection
