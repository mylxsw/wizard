@extends('layouts.user')

@section('title', __('common.user_info'))
@section('user-content')
    <form class="form-horizontal" method="post" action="{{ route('user:basic:handle') }}">
        {{ csrf_field() }}
        <div class="form-group">
            <label for="editor-email" class="col-sm-2 control-label">@lang('common.email')</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" value="{{ $user->email }}" id="editor-email"
                       name="email" placeholder="@lang('common.email')" readonly>
            </div>
        </div>
        <div class="form-group">
            <label for="editor-username" class="col-sm-2 control-label">@lang('common.username')</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" value="{{ $user->name }}" id="editor-username"
                       name="username" placeholder="@lang('common.username')">
            </div>
        </div>

        <div class="form-group">
            <div class="col-sm-offset-2 col-sm-10">
                <button type="submit" class="btn btn-success">@lang('common.btn_save')</button>
            </div>
        </div>
    </form>
@endsection
