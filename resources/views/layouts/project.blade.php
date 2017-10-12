@extends('layouts.default')
@section('container-style', 'container-fluid')
@section('title', $project->name)
@section('content')

    <div class="row marketing">
        <div class="col-lg-12 col-md-12 col-sm-12 wz-main-container">
            <div class="col-lg-3 col-md-3 col-sm-3">
                <div class="wz-project-title">
                    <a href="{{ wzRoute('project:home', ['id' => $project->id]) }}" class="wz-nav-item"
                        title="{{ $project->name }}">
                        {{ $project->name }}
                    </a>
                    @if(!Auth::guest() && (Auth::user()->can('page-add', $project) || Auth::user()->can('project-edit', $project)))
                        <div class="btn-group pull-right" style="margin-right: 20px;" role="group">
                            <a class="dropdown-toggle" data-toggle="dropdown"
                                    aria-haspopup="true" aria-expanded="false">
                                <span class="glyphicon glyphicon-option-horizontal" aria-hidden="true"></span>
                            </a>
                            <ul class="dropdown-menu">
                                @can('page-add', $project)
                                    <li>
                                        <a href="{!! wzRoute('project:doc:new:show', ['id' => $project->id, 'pid' => $pageID]) !!}">
                                            <span class="icon-maxcdn"></span>
                                            创建 @lang('common.markdown') 文档
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{!! wzRoute('project:doc:new:show', ['id' => $project->id, 'type' => 'swagger', 'pid' => $pageID]) !!}">
                                            <span class="icon-beaker"></span>
                                            创建 @lang('common.swagger') 文档
                                        </a>
                                    </li>
                                @endcan

                                @can('project-edit', $project)
                                    <li>
                                        <a href="{{ wzRoute('project:setting:show', ['id' => $project->id]) }}">
                                            <span class="glyphicon glyphicon-wrench"></span>
                                            项目设置
                                        </a>
                                    </li>
                                @endcan
                            </ul>
                        </div>
                    @endif
                </div>
                <ul class="nav nav-pills nav-stacked wz-left-nav {{-- hide --}}">
                    @include('components.navbar', ['navbars' => $navigators])
                </ul>
            </div>
            <div class="col-lg-9 col-md-9 col-sm-9 wz-panel-right">

                <div class="panel panel-default">
                    <div class="panel-body" style="padding: 10px 15px;">
                        @yield('page-content')
                    </div>
                </div>
                @stack('page-panel')
            </div>
        </div>
    </div>

@endsection

@push('script')
    <script src="/assets/js/navigator-tree.js?{{ resourceVersion() }}"></script>
    <script>
        // 侧边导航自动折叠
        $(function () {
            $.wz.navigator_tree($('.wz-left-nav'));
//            window.setTimeout(function () {
//                $('.wz-left-nav').removeClass('hide').addClass('animated fadeIn');
//            }, 20);

            // 重置窗口大小，避免内容过少无法撑开页面
            var resize_window = function () {
                $('.wz-panel-right').css('min-height', $(window).height() - $('.wz-top-navbar').height() - $('.footer').height() - 28+ "px");
            };

            resize_window();
            $(window).on('resize', function () {
                resize_window();
            });
        });
    </script>
@endpush