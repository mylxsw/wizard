@extends('layouts.default')

@section('container-style', 'container container-small')
@section('content')
    <div class="row wz-main-container-full">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">@lang('common.register')</div>
                <div class="panel-body">
                    <form class="form-horizontal" method="POST" action="{{ wzRoute('register') }}">
                        {{ csrf_field() }}

                        <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                            <label for="name" class="col-md-4 control-label">@lang('common.username')</label>

                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control"
                                       placeholder="真实姓名，用于成员之间协作"
                                       name="name" value="{{ old('name') }}" required autofocus>

                                @if ($errors->has('name'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                            <label for="email" class="col-md-4 control-label">@lang('common.email')</label>

                            <div class="col-md-6">
                                <input id="email" type="email"
                                       placeholder="注册后验证邮箱地址是否真实"
                                       class="form-control" name="email" value="{{ old('email') }}" required>

                                @if ($errors->has('email'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                            <label for="password" class="col-md-4 control-label">@lang('common.password')</label>

                            <div class="col-md-6">
                                <input id="password" type="password"
                                       placeholder="密码应该具有一定的复杂性"
                                       class="form-control" name="password" required>

                                @if ($errors->has('password'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="password-confirm" class="col-md-4 control-label">@lang('common.password_confirm')</label>

                            <div class="col-md-6">
                                <input id="password-confirm" type="password"
                                       placeholder="重复输入密码"
                                       class="form-control" name="password_confirmation" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">
                                    @lang('common.register')
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
