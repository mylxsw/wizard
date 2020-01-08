@extends('layouts.admin')

@section('title', '仪表盘')
@section('breadcrumb')
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ wzRoute('user:home') }}">@lang('common.home')</a></li>
        <li class="breadcrumb-item">系统管理</li>
        <li class="breadcrumb-item active">仪表盘</li>
    </ol>
@endsection
@section('admin-content')
    <div class="card card-white">
        <div class="card-header">总览</div>
        <div class="card-deck mb-3 text-center card-body">
            <div class="card mb-4 box-shadow text-white bg-success">
                <div class="card-header">
                    <h4 class="my-0 font-weight-normal">用户</h4>
                </div>
                <div class="card-body">
                    <h1 class="card-title pricing-card-title">{{ ($user['counts']['normal'] ?? 0) + ($user['counts']['admin'] ?? 0) }}</h1>
                    <ul class="list-unstyled mt-3 mb-4">
                        <li>{{ $user['counts']['normal'] ?? 0 }} 个普通用户</li>
                        <li>{{ $user['counts']['admin'] ?? 0 }} 个管理员</li>
                        <li>{{ $user['group_count'] ?? 0 }} 个用户组</li>
                    </ul>
                </div>
            </div>
            <div class="card mb-4 box-shadow text-white bg-info">
                <div class="card-header">
                    <h4 class="my-0 font-weight-normal">项目</h4>
                </div>
                <div class="card-body">
                    <h1 class="card-title pricing-card-title">{{ ($project['counts']['private'] ?? 0) + ($project['counts']['public'] ?? 0) }}</h1>
                    <ul class="list-unstyled mt-3 mb-4">
                        <li>{{ $project['counts']['private'] ?? 0 }} 个私有项目</li>
                        <li>{{ $project['counts']['public'] ?? 0 }} 个公开项目</li>
                        <li>{{ $project['catalog_count'] ?? 0 }} 个项目目录</li>
                    </ul>
                </div>
            </div>
            <div class="card mb-4 box-shadow text-white bg-warning">
                <div class="card-header">
                    <h4 class="my-0 font-weight-normal">文档</h4>
                </div>
                <div class="card-body">
                    <h1 class="card-title pricing-card-title">{{ ($document['counts']['swagger'] ?? 0) + ($document['counts']['markdown'] ?? 0) }}</h1>
                    <ul class="list-unstyled mt-3 mb-4">
                        <li>{{ $document['counts']['swagger'] ?? 0 }} 个 Swagger</li>
                        <li>{{ $document['counts']['markdown'] ?? 0 }} 个 Markdown</li>
                        <li>{{ $document['counts']['table'] ?? 0 }} 个 Table</li>
                        <li>{{ $document['comment_count'] ?? 0 }} 条评论</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div class="card mt-3 card-white">
        <div class="card-header">新增文档统计</div>
        <div class="card-body" id="visualization" style="padding: 0;"></div>
    </div>
@endsection

@push('stylesheet')
    <link href="{{ cdn_resource('/assets/vendor/vis/vis.min.css') }}" rel="stylesheet">
@endpush

@push('script')
    <script src="{{ cdn_resource('/assets/vendor/vis/vis.min.js') }}"></script>
    <script>
        $(function () {
            var container = document.getElementById('visualization');
            var items = [
                @foreach ($stats['document'] as $s)
                {
                    x: '{{ $s['month'] }}', y: {{ $s['document_count'] ?? 0 }} }
                @if(!$loop->last)
                ,
                @endif
                @endforeach
            ];

            new vis.Graph2d(container, new vis.DataSet(items), {
                style: 'bar',
                barChart: {width: 20, align: 'center'}, // align: left, center, right
                drawPoints: true,
                dataAxis: {
                    icons: true,
                    left: {
                        title: {
                            text: '新增文档数'
                        }
                    }
                },
                orientation: 'top'
            });

        });
    </script>
@endpush