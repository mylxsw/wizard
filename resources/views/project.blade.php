@extends('layouts.default')
@section('container-style', 'container container-fluid')
@section('content')
    @include('layouts.navbar')

    <div class="row marketing">
        <div class="col-lg-12">
            <div class="col-lg-3">
                <div class="btn-group wz-nav-control">
                    <div class="btn-group" role="group">
                        <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" >
                            <span class="glyphicon glyphicon glyphicon-plus" aria-hidden="true"></span>
                            新增
                            <span class="caret"></span>
                        </button>
                        <ul class="dropdown-menu">
                            <li><a href="{{ wzRoute('page-new-show', ['id' => $project->id]) }}">文档</a></li>
                            <li><a href="#">目录</a></li>
                        </ul>
                    </div>
                </div>
                <ul class="nav nav-pills nav-stacked">
                    @foreach($project->pages as $p)
                    <li class="{{ $p->id == $pageID ? 'active' : '' }}">
                        <a href="{{ wzRoute('project-home', ['id' =>  $project->id, 'p' => $p->id]) }}">{{ $p->title }}</a>
                    </li>
                    @endforeach
                </ul>
            </div>
            <div class="col-lg-9">
                @if($pageID !== 0)
                <nav class="wz-page-control clearfix">
                    <ul class="nav nav-pills pull-right">
                        <li role="presentation"><a href="{{ wzRoute('page-edit-show', ['id' => $project->id, 'page_id' => $pageItem->id]) }}">编辑</a></li>
                        <li role="presentation"><a href="#">详情</a></li>
                        <li role="presentation" class="dropdown">
                            <a class="dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
                                更多 <span class="caret"></span>
                            </a>
                            <ul class="dropdown-menu">
                                <li><a href="#">分享</a></li>
                                <li><a href="#">导出</a></li>
                                <li><a href="#">复制</a></li>
                                <li><a href="/page/1/setting">配置</a></li>
                            </ul>
                        </li>
                    </ul>
                </nav>
                <div class="panel panel-default">
                    <div class="panel-body">
                        <h1>{{ $pageItem->title }}</h1>
                        <div class="markdown-body" id="markdown-body">
                            <textarea id="append-test" style="display:none;">{{ $pageItem->content }}</textarea>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>

@endsection

@push('stylesheet')
<link href="/assets/vendor/editor-md/css/editormd.preview.css" rel="stylesheet"/>
@endpush

@push('script')

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
        editormd.markdownToHTML('markdown-body', {
            htmlDecode      : "style,script,iframe",
            tocm            : true,
            tocDropdown     : true,
            markdownSourceCode : true,
            emoji           : true,
            taskList        : true,
            tex             : true,
            flowChart       : true,
            sequenceDiagram : true
        });
    });
</script>
@endpush