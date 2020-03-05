@extends('layouts.default')

@section('title', '个人首页')
@section('container-style', 'container container-small')
@section('content')

    <div class="card mt-4">
        <div class="card-header">
            <div class="card-header-title">我的项目</div>
        </div>
        <div class="card-body">
            <div class="row marketing wz-main-container-full col-12">
                @foreach($projects ?? [] as $proj)
                    <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                        <a class="wz-box wz-box-project" href="{{ wzRoute('project:home', ['id'=> $proj->id]) }}" >
                            @if($proj->visibility == \App\Repositories\Project::VISIBILITY_PRIVATE)
                                <span title="@lang('project.privilege_private')" class="wz-box-tag fa fa-eye-slash"></span>
                            @endif
                            <p class="wz-title" title="{{ $proj->name }}【排序：{{ $proj->sort_level }}】">{{ $proj->name }}</p>
                            <p class="wz-page-count">{{ $proj->pages_count ?? '0' }} 个文档</p>
                            @if(!empty($proj->catalog_id))
                                <span title="所属目录" class="wz-box-tag pull-right wz-project-count">{{ $proj->catalog->name ?? '' }}</span>
                            @endif
                        </a>
                    </div>
                @endforeach
                @can('project-create')
                    <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                        <a class="wz-box wz-box-new" href="#"
                           data-toggle="modal" data-target="#wz-new-project">
                            <p class="wz-title"><span class="fa fa-plus"></span> @lang('project.new_project')</p>
                        </a>
                    </div>
                @else
                    <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                        <span class="wz-box-disabled">
                            <p class="wz-title" title="您没有创建项目的权限">
                                <span class="fa fa-ban"></span>
                                @lang('project.new_project')
                            </p>
                        </span>
                    </div>
                @endcan
            </div>
            <div class="row">
                <div class="wz-pagination">
                    {{ $projects->links() }}
                </div>
            </div>
        </div>
    </div>

    <div class="card mt-4 mb-4">
        <div class="card-header">最近活动</div>
        <div class="card-body" id="operation-log-recently"></div>
        <div class="card-footer text-center">
            <a href="#" class="wz-load-more">加载更多</a>
        </div>
        @include('components.doc-compare-script')
    </div>
    @can('project-create')
        @include('components.project-create')
    @endcan
@endsection

@push('script')
<script src="{{ cdn_resource('/assets/vendor/moment-with-locales.min.js') }}"></script>
<script>
    $(function() {
        // 最近活动加载
        moment.locale('zh-cn');

        var getRecentlyLogs = function (offset) {
            $('.wz-load-more').html('加载中...');
            $.wz.request('get', '{{ wzRoute('operation-log:recently', ['limit' => 'my']) }}', {offset: offset}, function (data) {
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
@endpush
