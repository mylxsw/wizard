@extends('layouts.login')
@section('title', __('common.register'))
@section('content')

    <form class="form-signin" method="POST" action="{{ wzRoute('register') }}">
        {{--<img class="mb-4" src="/assets/wizard.svg" alt="" height="100">--}}
        <h1 class="h3 mb-3 font-weight-normal">@lang('common.register')</h1>

        {{ csrf_field() }}

        @if (config('wizard.register_invitation'))
            <div class="text-left form-group{{ $errors->has('invitation_code') ? ' has-error' : '' }}">
                <label for="invitation_code" class="bmd-label-floating">邀请码</label>
                <input id="invitation_code" type="text" class="form-control" name="invitation_code" value="{{ old('invitation_code') }}" required autofocus>

                @if ($errors->has('invitation_code'))
                    <div class="invalid-feedback d-block">
                        {{ $errors->first('invitation_code') }}
                    </div>
                @endif
            </div>
        @endif

        <div class="text-left form-group{{ $errors->has('name') ? ' has-error' : '' }}">
            <label for="name" class="bmd-label-floating">@lang('common.username')</label>
            <input id="name" type="text" class="form-control" name="name" value="{{ old('name') }}" required autofocus>

            @if ($errors->has('name'))
                <div class="invalid-feedback d-block">
                    {{ $errors->first('name') }}
                </div>
            @endif
        </div>

        <div class="text-left form-group{{ $errors->has('email') ? ' has-error' : '' }}">
            <label for="email" class="bmd-label-floating">@lang('common.email')</label>
            <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required>

            @if ($errors->has('email'))
                <div class="invalid-feedback d-block">
                    {{ $errors->first('email') }}
                </div>
            @endif
        </div>

        <div class="text-left form-group{{ $errors->has('password') ? ' has-error' : '' }}">
            <label for="password" class="bmd-label-floating">@lang('common.password')</label>
            <input id="password" type="password" class="form-control" name="password" required>

            @if ($errors->has('password'))
                <div class="invalid-feedback d-block">
                    {{ $errors->first('password') }}
                </div>
            @endif
        </div>

        <div class="text-left form-group">
            <label for="password-confirm" class="bmd-label-floating">@lang('common.password_confirm')</label>
            <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required>
        </div>

        <button type="submit" class="btn btn-lg btn-primary btn-block btn-raised">
           @lang('common.register')
        </button>

        <a class="btn btn-link" href="{{ wzRoute('login') }}">
            已有账号，登录
        </a>

        <p class="mt-5 mb-3 text-muted">&copy; {{ date('Y') }} {{ config('wizard.copyright', 'AICODE.CC') }}</p>
    </form>

@endsection
