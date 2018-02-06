@extends('layouts.default')
@section('container-style', 'container-fluid')
@section('title', $project->name)
@section('content')

    <div class="row wz-main-container">
        <div class="col-3">
            <div class="wz-project-title">
                <a href="{{ wzRoute('project:home', ['id' => $project->id]) }}" class="wz-nav-item"
                    title="{{ $project->name }}">
                    {{ $project->name }}
                </a>
                @if(!Auth::guest() && (Auth::user()->can('page-add', $project) || Auth::user()->can('project-edit', $project)))
                    <div class="dropdown pull-right" style="margin-right: 20px;" role="group">
                        <button type="button" class="btn bmd-btn-icon " data-href="{!! wzRoute('project:doc:new:show', ['id' => $project->id, 'pid' => $pageID]) !!}" title="创建Markdown文档">
                            <i class="material-icons">add_to_photos</i>
                        </button>
                        <button type="button" class="btn bmd-btn-icon " data-href="{!! wzRoute('search:search', ['project_id' => $project->id]) !!}" title="搜索">
                            <i class="material-icons">search</i>
                        </button>
                        <button class="btn bmd-btn-icon dropdown-toggle" type="button" id="project-menu" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="material-icons">more_vert</i>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-left" aria-labelledby="project-menu" style="min-width: 13rem;">
                            @can('page-add', $project)
                                <a href="{!! wzRoute('project:doc:new:show', ['id' => $project->id, 'pid' => $pageID]) !!}" class="dropdown-item">
                                    <i class="icon-book mr-2"></i> 创建 @lang('common.markdown')
                                </a>
                                <a href="{!! wzRoute('project:doc:new:show', ['id' => $project->id, 'type' => 'swagger', 'pid' => $pageID]) !!}" class="dropdown-item">
                                    <i class="icon-align-justify mr-2"></i> 创建 @lang('common.swagger')
                                </a>
                            @endcan

                            @can('project-edit', $project)
                                <a href="{{ wzRoute('project:setting:show', ['id' => $project->id]) }}" class="dropdown-item">
                                    <i class="icon-cog mr-2"></i> 项目设置
                                </a>
                            @endcan
                        </ul>
                    </div>
                @endif
            </div>
            <ul class="nav nav-pills nav-stacked wz-left-nav list-group {{-- hide --}}">
                @include('components.navbar', ['navbars' => $navigators])
            </ul>
        </div>
        <div class="col-9 wz-panel-right">
            <div class="panel panel-default">
                <div class="panel-body" style="padding: 10px 15px;">
                    @yield('page-content')
                </div>
            </div>
            @stack('page-panel')
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
        });
    </script>
@endpush