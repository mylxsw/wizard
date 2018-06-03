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
            tocm: true,
            tocDropdown: false,
            markdownSourceCode: true,
            taskList: true,
            tex: true,
            htmlDecode : 'style,script,iframe,sub,sup|on*',
            flowChart: true,
            sequenceDiagram: true
        });

        // TOC导航插入到侧边随滚动展示
        window.setTimeout(function () {
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
    });
</script>
@endpush