@extends('layouts.user')

@section('title', __('common.user_info'))
@section('user-content')
    <div class="card">
        <div class="card-body">
            <form class="form-horizontal" method="post" action="{{ wzRoute('user:basic:handle') }}">
                {{ csrf_field() }}
                <div class="form-group">
                    <label for="editor-email" class="bmd-label-floating">@lang('common.email')</label>
                    <input type="text" class="form-control" value="{{ $user->email }}" id="editor-email"
                           name="email" readonly>
                </div>
                <div class="form-group">
                    <label for="editor-username" class="bmd-label-floating">@lang('common.username')</label>
                    <input type="text" class="form-control" value="{{ $user->name }}" id="editor-username"
                           name="username" >
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
