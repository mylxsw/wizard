@extends('layouts.default')

@section('title', '首页')
@section('container-style', 'container container-small')
@section('content')

    <div class="card mt-4">
        <div class="card-header">公共主页</div>
        <div class="card-body">

            <div class="row marketing wz-main-container-full">
                @unless(Auth::guest())
                    <div class="col alert alert-info alert-dismissible" data-alert-id="public-home-tip">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        提示： 该页面为公共主页，如果要创建项目，请到 <a href="{{ wzRoute('user:home') }}">@lang('common.user_home')</a>。
                    </div>
                @endunless
                <div class="row col-12">
                    @foreach($projects ?? [] as $proj)
                        <div class="col-3">
                            <a class="wz-box" href="{{ wzRoute('project:home', ['id'=> $proj->id]) }}">
                                @include('components.project-tag', ['proj' => $proj])
                                <p class="wz-title" title="{{ $proj->name }}">{{ $proj->name }}</p>
                            </a>
                        </div>
                    @endforeach
                </div>
                <div class="wz-pagination">
                    {{ $projects->links() }}
                </div>
            </div>
        </div>
    </div>

@endsection