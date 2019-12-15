@extends('layouts.user')

@section('title', __('common.change_password'))
@section('breadcrumb')
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ wzRoute('user:home') }}">@lang('common.home')</a></li>
        <li class="breadcrumb-item">个人中心</li>
        <li class="breadcrumb-item active">@lang('common.change_password')</li>
    </ol>
@endsection
@section('user-content')
    <div class="card card-white">
        <div class="card-body">
            <form class="form-horizontal" method="post" action="{{ wzRoute('user:password:handle') }}">
                {{ csrf_field() }}
                <div class="form-group">
                    <label for="editor-original_password" class="bmd-label-floating">@lang('passwords.original_password')</label>
                    <input type="password" class="form-control" value="{{ old('original_password') }}"
                           id="editor-original_password"
                           name="original_password" >
                </div>
                <div class="form-group">
                    <label for="editor-new-password" class="bmd-label-floating">@lang('passwords.new_password')</label>
                    <input type="password" class="form-control" id="editor-new-password"
                           name="password" value="{{ old('password') }}">
                </div>
                <div class="form-group">
                    <label for="editor-new-password-confirm" class="bmd-label-floating">@lang('passwords.new_password_confirm')</label>
                    <input type="password" class="form-control" id="editor-new-password-confirm"
                           name="password_confirmation" value="{{ old('password_confirmation') }}">
                </div>

                <div class="form-group">
                    <div class="col-sm-offset-2 col-sm-10">
                        <button type="submit" class="btn btn-success btn-raised">@lang('common.btn_save')</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
