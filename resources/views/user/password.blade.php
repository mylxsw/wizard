@extends('layouts.user')

@section('title', __('common.change_password'))
@section('user-content')
    <form class="form-horizontal" method="post" action="{{ route('user:password:handle') }}">
        {{ csrf_field() }}
        <div class="form-group">
            <label for="editor-original_password" class="col-sm-2 control-label">@lang('passwords.original_password')</label>
            <div class="col-sm-10">
                <input type="password" class="form-control" value="{{ old('original_password') }}"
                       id="editor-original_password"
                       name="original_password" placeholder="@lang('passwords.original_password')">
            </div>
        </div>
        <div class="form-group">
            <label for="editor-new-password" class="col-sm-2 control-label">@lang('passwords.new_password')</label>
            <div class="col-sm-10">
                <input type="password" class="form-control" id="editor-new-password"
                       name="password" value="{{ old('password') }}" placeholder="@lang('passwords.new_password')">
            </div>
        </div>
        <div class="form-group">
            <label for="editor-new-password-confirm" class="col-sm-2 control-label">@lang('passwords.new_password_confirm')</label>
            <div class="col-sm-10">
                <input type="password" class="form-control" id="editor-new-password-confirm"
                       name="password_confirmation" {{ old('password_confirmation') }} placeholder="@lang('passwords.new_password_confirm')">
            </div>
        </div>

        <div class="form-group">
            <div class="col-sm-offset-2 col-sm-10">
                <button type="submit" class="btn btn-success">@lang('common.btn_save')</button>
            </div>
        </div>
    </form>
@endsection
