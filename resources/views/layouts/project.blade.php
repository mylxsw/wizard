@extends('layouts.default')
@section('container-style', 'container-fluid')
@section('title', $project->name)
@section('content')

    <div class="row wz-main-container">
        <div class="col-12 wz-left-main-full">
            <a class="wz-left-main-switch btn"><i class="fa fa-angle-double-down"></i> </a>
        </div>
        <div class="col-12 col-lg-3 wz-left-main">
            <div class="wz-project-title">
                <a href="{{ wzRoute('project:home', ['id' => $project->id]) }}" class="wz-nav-item"
                    title="{{ $project->name }}">
                    {{ $project->name }}
                </a>
                @php
                $hasEditPrivilege = !Auth::guest() && (Auth::user()->can('page-add', $project) || Auth::user()->can('project-edit', $project));
                @endphp
                <div class="dropdown pull-right" style="margin-right: 20px;" role="group">
                    @if($hasEditPrivilege)
                        <button class="btn bmd-btn-icon dropdown-toggle" type="button" id="new-document" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="material-icons">add_to_photos</i>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-left" aria-labelledby="new-document" style="min-width: 13rem;">
                            <a href="{!! wzRoute('project:doc:new:show', ['id' => $project->id, 'pid' => $pageID]) !!}" class="dropdown-item">
                                <i class="fa fa-book mr-2"></i> 创建 @lang('common.markdown')
                            </a>
                            <a href="{!! wzRoute('project:doc:new:show', ['id' => $project->id, 'type' => 'swagger', 'pid' => $pageID]) !!}" class="dropdown-item">
                                <i class="fa fa-align-justify mr-2"></i> 创建 @lang('common.swagger')
                            </a>
                        </ul>
                        <button type="button" class="btn bmd-btn-icon " data-href="{!! wzRoute('search:search', ['project_id' => $project->id]) !!}" title="搜索">
                            <i class="material-icons">search</i>
                        </button>
                    @endif
                    @if(!Auth::guest())
                        <button type="button" class="btn bmd-btn-icon" data-method="post" data-href="{{ wzRoute('project:favorite', ['id' => $project->id, 'action' => $isFavorited ? 'unfav':'fav']) }}" title="{{ $isFavorited ? '取消关注' : '关注该项目' }}">
                            <i class="material-icons {{ $isFavorited ? 'wz-box-tag-star' : '' }}">star</i>
                        </button>
                    @endif
                    @if($hasEditPrivilege)
                        @can('project-edit', $project)
                        <button class="btn bmd-btn-icon" type="button" data-href="{{ wzRoute('project:setting:show', ['id' => $project->id]) }}" title="项目设置">
                            <i class="material-icons">settings</i>
                        </button>
                        @endcan
                    @endif
                </div>
            </div>
            <ul class="nav nav-pills nav-stacked wz-left-nav {{-- hide --}}">
                @include('components.navbar', ['navbars' => $navigators])
            </ul>
        </div>
        <div class="col-12 col-lg-9 wz-panel-right">
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