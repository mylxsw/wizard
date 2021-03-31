@extends("layouts.single")
@section('title', '批量导出')
@section('page-content')
    @foreach($chapters as $chap)
    <div class="markdown-body" id="markdown-body-{{ $chap->id }}">
        <textarea class="append-test" style="display:none;">
# {{ $chap->title }}
{{ $chap->content }}
---
        </textarea>
    </div>
    @endforeach
@endsection

@push('stylesheet')
    <link href="{{ cdn_resource('/assets/vendor/editor-md/css/editormd.preview.css') }}" rel="stylesheet"/>
    <link href="{{ cdn_resource('/assets/vendor/viewer/viewer.min.css') }}" rel="stylesheet" />
@endpush

@push('script')
    <script src="{{ cdn_resource('/assets/vendor/bootstrap-treeview.js') }}"></script>
    <script src="{{ cdn_resource('/assets/vendor/editor-md/lib/prettify.min.js') }}"></script>
    {{-- 从 2019-12-16T21:54:00+08:00 开始创建的新文档，使用最新的 marked 库，对 Markdown 文档格式要求更为严格一些，也更加规范，这里是对之前已经创建的不符合规范的文档做一个兼容 --}}
    @if(markdownCompatibilityStrict($pageItem ?? null))
        <script src="{{ cdn_resource('/assets/vendor/editor-md/lib/marked.min.js') }}"></script>
    @else
        <script src="{{ cdn_resource('/assets/vendor/editor-md/lib/marked-0.3.3.min.js') }}"></script>
    @endif
    <script src="{{ cdn_resource('/assets/vendor/editor-md/lib/raphael.min.js') }}"></script>
    <script src="{{ cdn_resource('/assets/vendor/editor-md/lib/underscore.min.js') }}"></script>
    <script src="{{ cdn_resource('/assets/vendor/editor-md/lib/sequence-diagram.min.js') }}"></script>
    <script src="{{ cdn_resource('/assets/vendor/editor-md/lib/flowchart.min.js') }}"></script>
    <script src="{{ cdn_resource('/assets/vendor/editor-md/lib/jquery.flowchart.min.js') }}"></script>
    <script src="{{ cdn_resource('/assets/vendor/mermaid.min.js') }}"></script>
    <script src="{{ cdn_resource('/assets/vendor/editor-md/editormd.js') }}?{{ resourceVersion() }}"></script>
    <script src="{{ cdn_resource('/assets/vendor/viewer/viewer.min.js') }}"></script>

    <script type="text/javascript">
        $(function () {
            // 初始化 Mermaid
            // mermaid.initialize({startOnLoad:true});
            mermaid.init(undefined, $(".markdown-body .mermaid"));

            editormd.defaults.resourcesVersion = "{{ resourceVersion() }}";
            // 内容区域解析markdown
            editormd.katexURL  = {
                css : "{{ cdn_resource('/assets/vendor/katex-0.11.min') }}",
                js  : "{{ cdn_resource('/assets/vendor/katex-0.11.min') }}"
            }
            @foreach($chapters as $chap)
            editormd.markdownToHTML('markdown-body-{{ $chap->id }}', {
                tocm: true,
                toc: true,
                tocDropdown: false,
                markdownSourceCode: true,
                taskList: true,
                tex: true,
                htmlDecode : true,
                flowChart: true,
                sequenceDiagram: true
            });
            @endforeach
        });
    </script>
@endpush