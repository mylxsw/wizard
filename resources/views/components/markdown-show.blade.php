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
<script src="{{ cdn_resource('/assets/vendor/mermaid.js') }}"></script>
<script src="{{ cdn_resource('/assets/vendor/editor-md/editormd.js') }}?{{ resourceVersion() }}"></script>
<script src="{{ cdn_resource('/assets/vendor/viewer/viewer.min.js') }}"></script>

<script type="text/javascript">
    $(function () {
        // 鼠标经过提示效果
        $('[data-toggle="tooltip"]').tooltip({
            delay: {"show": 500, "hide": 100}
        });

        // 初始化 Mermaid
        // mermaid.initialize({startOnLoad:true});
        mermaid.init(undefined, $(".markdown-body .mermaid"));

        editormd.defaults.resourcesVersion = "{{ resourceVersion() }}";
        // 内容区域解析markdown
        editormd.katexURL  = {
            css : "{{ cdn_resource('/assets/vendor/katex-0.11.min') }}",
            js  : "{{ cdn_resource('/assets/vendor/katex-0.11.min') }}"
        }
        editormd.markdownToHTML('markdown-body', {
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


        window.setTimeout(function () {
            // TOC导航插入到侧边随滚动展示
            var tocElement = $('.markdown-body > .markdown-toc');
            if (tocElement.length < 1) {
                return ;
            }

            $('body').append('<div id="wz-toc-container" class="d-none"><span class="fa fa-th-list"></span>' + tocElement.html() + '</div>');

            $(window).scroll(function () {
                var tocContainer = $('#wz-toc-container');

                if ($(window).scrollTop() > 300) {
                    tocContainer.removeClass('d-none');
                } else {
                    tocContainer.addClass('d-none');
                }
            });
        }, 0);

        window.setTimeout(function () {
            // 延迟加载iframe，避免阻塞页面加载
            window.setTimeout($.wz.loadIframe, 1000);

            // 表格超宽展示优化
            $("#markdown-body table").each(function() {
                if ($(this)[0].scrollWidth > $('#markdown-body').width()) {
                    $(this).wrap("<div class='wz-wrap-table'></div>");
                }
            });
            $('#markdown-body .wz-wrap-table').prepend('<div class="control-area"><button class="btn btn-primary wz-wrap-table-flow"><i class="fa fa-arrows-h"></i></button><button class="btn btn-primary wz-wrap-table-open"><i class="fa fa-search-plus"></i></button></div>');
            $('#markdown-body').on('click', '.wz-wrap-table-open', function () {
                if ($(this).data('status') === 'open') {
                    $(this).data('status', 'close');
                    $(this).html('<i class="fa fa-search-plus">');
                    $(this).parents('.wz-wrap-table').removeClass('wz-table-float').css('max-height', '100%');
                } else {
                    $(this).html('<i class="fa fa-search-minus">');
                    $(this).data('status', 'open');
                    $(this).parents('.wz-wrap-table').addClass('wz-table-float').css('max-height', window.innerHeight - 100);
                }

            }).on('click', '.wz-wrap-table-flow', function () {
                if ($(this).data('status') === 'open') {
                    $(this).data('status', 'close');
                    $(this).html("<i class='fa fa-arrows-h'></i>");
                    $(this).parents('.wz-wrap-table').find('table').css('word-break', 'keep-all')
                } else {
                    $(this).data('status', 'open');
                    $(this).html("<i class='fa fa-arrows-v'></i>");
                    $(this).parents('.wz-wrap-table').find('table').css('word-break', 'break-all');
                }
            });

            // sql-create 标签解析
            $.wz.sqlCreateSyntaxParser('#markdown-body .wz-sql-create');

            // 图片放大查看
            new Viewer(document.getElementById('markdown-body'));
        }, 0);
    });
</script>
@endpush