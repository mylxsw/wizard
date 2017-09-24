@extends('layouts.default')

@section('title', '首页')
@section('container-style', 'container container-small')
@section('content')

    @include('layouts.navbar', ['hideGlobalAlert' => true])

    <div class="row marketing">
        <div class="col-lg-12">
            @unless(Auth::guest())
                <div class="alert alert-info alert-dismissible" data-alert-id="public-home-tip">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    提示： 该页面为公共主页，如果要创建项目，请到 <a href="{{ wzRoute('user:home') }}">@lang('common.user_home')</a>。
                </div>
            @endunless
            @foreach($projects ?? [] as $proj)
                <div class="col-lg-3">
                    <a class="wz-box" href="{{ wzRoute('project:home', ['id'=> $proj->id]) }}"
                       title="{{ $proj->description }}">
                        @include('components.project-tag', ['proj' => $proj])
                        <p class="wz-title">{{ $proj->name }}</p>
                    </a>
                </div>
            @endforeach
        </div>
        <div class="wz-pagination">
            {{ $projects->links() }}
        </div>
    </div>

@endsection