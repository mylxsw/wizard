@extends('layouts.default')
@section('container-style', 'container')
@section('content')
    @include('layouts.navbar')

    <div class="row marketing">
        <div class="col-lg-12">
            <div class="col-lg-3">

                <div id="wz-left-nav"></div>
                <ul class="nav nav-pills nav-stacked wz-left-nav">
                    <li class="{{ $pageID === 0 ? 'active' : '' }}">
                        <a href="{{ wzRoute('project:home', ['id' => $project->id]) }}" class="wz-nav-item">
                            <span class="glyphicon glyphicon-th-large"></span>
                            {{ $project->name }}
                            @if($project->visibility == \App\Repositories\Project::VISIBILITY_PRIVATE)
                                <span title="私有项目" class="pull-right wz-box-tag glyphicon glyphicon-eye-close"></span>
                            @endif
                        </a>
                    </li>
                    @include('components.navbar', ['navbars' => $navigators])
                </ul>
            </div>
            <div class="col-lg-9">

                <nav class="wz-page-control clearfix">
                    @can('page-add', $project)
                        <div class="btn-group wz-nav-control">
                            <div class="btn-group" role="group">
                                <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown"
                                        aria-haspopup="true" aria-expanded="false">
                                    <span class="glyphicon glyphicon glyphicon-plus" aria-hidden="true"></span>
                                    新增 <span class="caret"></span>
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a href="{{ wzRoute('project:page:new:show', ['id' => $project->id]) }}">文档</a>
                                    </li>
                                    <li><a href="#">目录</a></li>
                                </ul>
                            </div>
                            @can('project-setting', $project)
                                <a class="btn btn-default"
                                   href="{{ wzRoute('project:setting:show', ['id' => $project->id]) }}">项目配置</a>
                            @endcan
                        </div>
                    @endcan
                </nav>
                <div class="panel panel-default">
                    <div class="panel-body">
                        @if($pageID !== 0)
                            <nav class="wz-page-control clearfix">
                                <h1 class="wz-page-title">{{ $pageItem->title }}</h1>
                                <ul class="nav nav-pills pull-right">
                                    @if($pageID !== 0)
                                        @can('page-edit', $pageItem)
                                            <li role="presentation"><a
                                                        href="{{ wzRoute('project:page:edit:show', ['id' => $project->id, 'page_id' => $pageItem->id]) }}">编辑</a>
                                            </li>
                                        @endcan
                                    @endif
                                    <li role="presentation" class="dropdown">
                                        <a class="dropdown-toggle" data-toggle="dropdown" href="#" role="button"
                                           aria-haspopup="true" aria-expanded="false">
                                            更多 <span class="caret"></span>
                                        </a>
                                        <ul class="dropdown-menu">
                                            <li><a href="#">分享</a></li>
                                            <li><a href="#">导出</a></li>
                                            <li><a href="#">复制</a></li>
                                        </ul>
                                    </li>
                                </ul>
                            </nav>
                            <div class="markdown-body" id="markdown-body">
                                <textarea id="append-test" style="display:none;">{{ $pageItem->content }}</textarea>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('stylesheet')
<link href="/assets/vendor/editor-md/css/editormd.preview.css" rel="stylesheet"/>
@endpush

@push('script')
<script src="/assets/vendor/bootstrap-treeview.js"></script>
<script src="/assets/vendor/editor-md/lib/marked.min.js"></script>
<script src="/assets/vendor/editor-md/lib/prettify.min.js"></script>

<script src="/assets/vendor/editor-md/lib/raphael.min.js"></script>
<script src="/assets/vendor/editor-md/lib/underscore.min.js"></script>
<script src="/assets/vendor/editor-md/lib/sequence-diagram.min.js"></script>
<script src="/assets/vendor/editor-md/lib/flowchart.min.js"></script>
<script src="/assets/vendor/editor-md/lib/jquery.flowchart.min.js"></script>
<script src="/assets/vendor/editor-md/editormd.min.js"></script>

<script type="text/javascript">
    $(function () {
        // 鼠标经过提示效果
        $('[data-toggle="tooltip"]').tooltip({
            delay: {"show": 500, "hide": 100}
        });

        // 内容区域解析markdown
        editormd.markdownToHTML('markdown-body', {
            htmlDecode: "style,script,iframe",
            tocm: true,
            tocDropdown: true,
            markdownSourceCode: true,
            emoji: true,
            taskList: true,
            tex: true,
            flowChart: true,
            sequenceDiagram: true
        });
    });
</script>
@endpush