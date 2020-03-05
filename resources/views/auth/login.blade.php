@extends('layouts.login')
@section('title', __('common.login'))
@section('content')
    <form class="form-signin" method="POST" action="{{ wzRoute('login') }}">
        {{--<img class="mb-4" src="/assets/wizard.svg" alt="" height="100">--}}
        <h1 class="h3 mb-3 font-weight-normal">@lang('common.login')</h1>

        {{ csrf_field() }}

        <div class="text-left form-group{{ $errors->has('email') ? ' has-error' : '' }}">
            <label for="email" class="bmd-label-floating">@lang('common.email')</label>
            <input id="email" type="text" class="form-control" name="email" value="{{ old('email') }}" required autofocus>

            @if ($errors->has('email'))
                <div class="invalid-feedback" style="display: block;">
                    {{ $errors->first('email') }}
                </div>
            @endif
        </div>

        <div class="text-left form-group{{ $errors->has('password') ? ' has-error' : '' }}">
            <label for="password" class="bmd-label-floating">@lang('common.password')</label>
            <input id="password" type="password" class="form-control" name="password" required>

            @if ($errors->has('password'))
                <div class="invalid-feedback" style="display: block;">
                    {{ $errors->first('password') }}
                </div>
            @endif
        </div>

        <div class="form-group ">
            <div class="checkbox mb-3">
                <label>
                    <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}> 下次自动登录
                </label>
            </div>
        </div>

        <button type="submit" class="btn btn-lg btn-primary btn-block btn-raised">
            @lang('common.login')
        </button>

        @if (!ldap_enabled())
            <a class="btn btn-link" href="{{ wzRoute('register') }}">
                @lang('common.register')
            </a>

            <a class="btn btn-link" href="{{ wzRoute('password.request') }}">
                @lang('common.password_back')?
            </a>
        @endif

        <p class="mt-5 mb-3 text-muted">&copy; {{ date('Y') }} {{ config('wizard.copyright', 'AICODE.CC') }}</p>
    </form>
@endsection
