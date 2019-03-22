@extends('layouts.default')

@section('title', '首页')
@section('container-style', 'container container-small')
@section('content')

    <div class="card mt-4 mb-4">
        <div class="card-header">
            <div class="card-header-title">
                @if (!empty($catalog))
                    <a class="badge badge-info badge-pill" href="{{ wzRoute('home') }}">
                        #{{ $catalog->name }}
                    </a>
                @endif
            </div>
            <div class="card-header-operation">
                <div class="bmd-form-group bmd-collapse-inline pull-right">
                    <i class="material-icons search-btn" data-input="#search-input">search</i>
                    <span id="search-input" style="{{ empty($name) ? 'display: none;' : '' }}">
                        <input class="form-control" type="text" id="search" name="search_name" placeholder="搜索文档" value="{{ $name ?? '' }}">
                    </span>
                </div>
            </div>
        </div>
        <div class="card-body">

            <div class="row marketing wz-main-container-full">
                @unless(Auth::guest() || !empty($catalog_id))
                    <div class="col alert alert-info alert-dismissible" data-alert-id="public-home-tip">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        提示： 该页面为公共主页，如果要创建项目，请到 <a href="{{ wzRoute('user:home') }}">@lang('common.user_home')</a>。
                    </div>
                @endunless
                @if(!empty($catalogs))
                    <div class="row col-12">
                        @foreach($catalogs ?? [] as $cat)
                            <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                                <a class="wz-box wz-box-catalog" href="{{ wzRoute('home', ['catalog' => $cat->id]) }}">
                                    <span title="项目数" class="wz-box-tag pull-right wz-project-count">{{ $cat->projects_count }} 个项目</span>
                                    <p class="wz-title" title="{{ $cat->name }}【排序：{{ $cat->sort_level }}】">{{ $cat->name }}</p>
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
                                <p class="wz-title" title="{{ $proj->name }}【排序：{{ $proj->sort_level }}】">{{ $proj->name }}</p>
                                <p class="wz-page-count">{{ $proj->pages_count ?? '0' }} 个文档</p>
                                @if (!empty($name)) {{-- 搜索模式下，所有项目平级展示，因此要输出项目所属的目录名称 --}}
                                    <span title="所属目录" class="wz-box-tag pull-right wz-project-count">{{ $proj->catalog->name ?? '' }}</span>
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
                            <a class="wz-box wz-box-project" href="{{ wzRoute('project:home', ['id'=> $proj->id]) }}">
                                @include('components.project-tag', ['proj' => $proj])
                                <span title="关注该项目" class="wz-box-tag pull-right fa fa-star wz-box-tag-star"></span>
                                <p class="wz-title" title="{{ $proj->name }}【排序：{{ $proj->sort_level }}】">{{ $proj->name }}</p>
                                <p class="wz-page-count">{{ $proj->pages_count ?? '0' }} 个文档</p>
                                <span title="所属目录" class="wz-box-tag pull-right wz-project-count">{{ $proj->catalog->name ?? '' }}</span>
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

                    $('#operation-log-recently .wz-operation-log-time').map(function() {
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

            $('.wz-load-more').click(function(e) {
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
        $(function () {
            $('.search-btn').on('click', function () {
                var inputItem = $($(this).data('input'));
                inputItem.fadeToggle();
                inputItem.find('input').focus();
            });

            $('#search-input').find('input').keydown(function (event) {
                if (event.keyCode === 13) {
                    {{--window.location = "{{ route('home') }}?catalog={{ $catalog_id }}&name=" + encodeURIComponent($(this).val().trim());--}}
                    // 首页文档目录搜索修改为文档搜素
                    window.location = "{{ wzRoute('search:search') }}?keyword=" + encodeURIComponent($(this).val().trim());
                }
            }).blur(function () {
                var value = $(this).val().trim();
                if (value === '') {
                    $(this).parent('#search-input').fadeToggle();
                }
            });
        });
    </script>
@endpush