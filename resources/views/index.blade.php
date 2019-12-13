@extends('layouts.default')

@section('title', '首页')
@section('container-style', 'container container-small')
@section('content')

    <div class="card mt-4 mb-4">
        @if (!empty($catalog))
        <div class="card-header">
            <div class="card-header-operation">
                <div class="bmd-form-group bmd-collapse-inline pull-left">
                    <a href="{{ wzRoute('home') }}" class="material-icons" data-toggle="tooltip" title="返回首页">home</a>
                </div>
                <div class="bmd-form-group bmd-collapse-inline pull-right" style="padding-top: 3px;">
                    <a class="badge badge-success badge-pill" href="{{ wzRoute('home', ['catalog' => $catalog_id]) }}" data-toggle="tooltip" title="点击此处刷新页面">#{{ $catalog->name }}</a>
                </div> 
            </div>
        </div>
        @endif
        <div class="card-body">

            <div class="row marketing wz-main-container-full">
                @unless(Auth::guest() || !empty($catalog_id))
                    <div class="col alert alert-info alert-dismissible" data-alert-id="public-home-tip">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
                                    aria-hidden="true">&times;</span></button>
                        提示： 该页面为公共主页，如果要创建项目，请到 <a href="{{ wzRoute('user:home') }}">@lang('common.user_home')</a>。
                    </div>
                @endunless
                @if(!empty($catalogs))
                    <div class="row col-12">
                        @foreach($catalogs ?? [] as $cat)
                            <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                                <a class="wz-box wz-box-catalog" href="{{ wzRoute('home', ['catalog' => $cat->id]) }}">
                                    <span title="项目数" class="wz-box-tag pull-right wz-project-count">{{ $cat->projects_count }} 个项目</span>
                                    <p class="wz-title"
                                       title="{{ $cat->name }}【排序：{{ $cat->sort_level }}】">{{ $cat->name }}</p>
                                </a>
                            </div>
                        @endforeach
                    </div>
                @endif

                <div class="row col-12">
                    @foreach($projects ?? [] as $proj)
                        <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                            <a class="wz-box wz-box-project" href="{{ wzRoute('project:home', ['id'=> $proj->id]) }}">
                                @include('components.project-tag', ['proj' => $proj])
                                {{--@if(!empty($favorites) && $favorites->contains('id', $proj->id))--}}
                                {{--<span title="关注该项目" class="wz-box-tag pull-right fa fa-star wz-box-tag-star"></span>--}}
                                {{--@endif--}}
                                <p class="wz-title"
                                   title="{{ $proj->name }}【排序：{{ $proj->sort_level }}】">{{ $proj->name }}</p>
                                <p class="wz-page-count">{{ $proj->pages_count ?? '0' }} 个文档</p>
                                @if (!empty($name)) {{-- 搜索模式下，所有项目平级展示，因此要输出项目所属的目录名称 --}}
                                <span title="所属目录"
                                      class="wz-box-tag pull-right wz-project-count">{{ $proj->catalog->name ?? '' }}</span>
                                @endif
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

    @if(!Auth::guest() && empty($name))

        {{-- 非搜索模式，同时用于有关注的项目，则展示 --}}
        @if(!empty($favorites) && $favorites->count() > 0 && empty($name))
            <div class="card mb-4">
                <div class="card-header">我关注的</div>
                <div class="card-body">
                    <div class="row col-12">
                        @foreach($favorites as $proj)
                            <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                                <a class="wz-box wz-box-project"
                                   href="{{ wzRoute('project:home', ['id'=> $proj->id]) }}">
                                    @include('components.project-tag', ['proj' => $proj])
                                    <span title="关注该项目" class="wz-box-tag pull-right fa fa-star wz-box-tag-star"></span>
                                    <p class="wz-title"
                                       title="{{ $proj->name }}【排序：{{ $proj->sort_level }}】">{{ $proj->name }}</p>
                                    <p class="wz-page-count">{{ $proj->pages_count ?? '0' }} 个文档</p>
                                    <span title="所属目录"
                                          class="wz-box-tag pull-right wz-project-count">{{ $proj->catalog->name ?? '' }}</span>
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif

        @if (!empty($tags) && $tags->count() > 0)
            <div class="card mb-4">
                <div class="card-header">标签</div>
                <div class="card-body">
                    <div class="wz-tag-container">
                        @foreach($tags as $tag)
                            <span class="tm-tag tm-tag-disabled">
                            <a href="{{ wzRoute('search:search', ['tag' => $tag->name]) }}">{{ $tag->name }}</a>
                            <span style="color: #6c6c6c;">[{{ $tag->pages_count ?? 0 }}]</span>
                        </span>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif

        <div class="card mb-4">
            <div class="card-header">最近活动</div>
            <div class="card-body" id="operation-log-recently"></div>
            <div class="card-footer text-center">
                <a href="#" class="wz-load-more">加载更多</a>
            </div>
        </div>
        @include('components.doc-compare-script')
    @endif

    <div class="wz-version-suggest">
        <ul class="wz-version-suggest-items">
           <li>1、实现了对 mermaid 绘制流程图，序列图，饼图，类图，状态图等各种图表的支持。<a target="_blank" href="https://github.com/mylxsw/wizard/wiki/%E6%B5%81%E7%A8%8B%E5%9B%BE%EF%BC%8C%E5%BA%8F%E5%88%97%E5%9B%BE%EF%BC%8C%E9%A5%BC%E5%9B%BE%EF%BC%8CTex-LaTex-%E7%A7%91%E5%AD%A6%E5%85%AC%E5%BC%8F%E6%94%AF%E6%8C%81"> 查看使用说明 </a></li>
           <li>2、增加了对数据库数据结构展示卡片的支持，现在，你可以直接将 SQL 建表语句放置在代码块中，Wizard 将会为你转换为表格展示。<a target="_blank" href="https://github.com/mylxsw/wizard/wiki/%E6%95%B0%E6%8D%AE%E7%BB%93%E6%9E%84%E5%8D%A1%E7%89%87%E5%B1%95%E7%A4%BA%E6%94%AF%E6%8C%81"> 查看使用说明 </a> </li>
           <li>3、Markdown 文档页面中的表格样式优化</li>
        </ul>
    </div>

@endsection

@push('script')
    @if(!Auth::guest() && empty($name))
        <script src="/assets/vendor/moment-with-locales.min.js"></script>
        <script>
            $(function () {
                moment.locale('zh-cn');

                var getRecentlyLogs = function (offset) {
                    $('.wz-load-more').html('加载中...');
                    $.wz.request('get', '{{ wzRoute('operation-log:recently', ['catalog' => $catalog_id,]) }}', {offset: offset}, function (data) {
                        $('#operation-log-recently').append(data);

                        $('#operation-log-recently .wz-operation-log-time').map(function () {
                            $(this).html(moment($(this).prop('title'), 'YYYY-MM-DD hh:mm:ss').fromNow());
                        });

                        if (data.trim() === "") {
                            $('.wz-load-more').parent().html('没有更多了...');
                        } else {
                            $('.wz-load-more').html('加载更多');
                        }

                    }, null, 'html');
                };

                // 初次加载最近操作日志
                getRecentlyLogs(0);

                $('.wz-load-more').click(function (e) {
                    e.preventDefault();
                    var offset = $('#operation-log-recently .wz-operation-log-time').size();
                    if (offset > 100) {
                        $(this).parent().html('只能加载这么多了...');
                        return;
                    }

                    getRecentlyLogs(offset);
                });

            });
        </script>
    @endif

    <script>
        // 新版本更新时，弹出版本更新内容提示框，告知用户更新了哪些内容
        $(function () {
            var currentVersion = '{{ config('wizard.version') }}';
            var storedVersion = store.get('feature_suggest');

            if (currentVersion !== storedVersion) {
                window.setTimeout(function () {
                    layer.open({
                        type: 1,
                        title: 'v' + currentVersion + '更新内容',
                        shade: false,
                        skin: 'layui-layer-molv',
                        offset: 'rb',
                        content: $('.wz-version-suggest').html(),
                        area: ['420px'],
                        cancel: function () {
                            store.set('feature_suggest', currentVersion);
                        }
                    });
                }, 500);
            }
        });
    </script>
@endpush