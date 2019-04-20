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
<script src="/assets/vendor/editor-md/editormd.js?{{ resourceVersion() }}"></script>

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
                    $(this).parents('.wz-wrap-table').removeClass('wz-table-float');
                } else {
                    $(this).html('<i class="fa fa-search-minus">');
                    $(this).data('status', 'open');
                    $(this).parents('.wz-wrap-table').addClass('wz-table-float');
                }

            }).on('click', '.wz-wrap-table-flow', function () {
                if ($(this).data('status') === 'open') {
                    $(this).data('status', 'close');
                    $(this).html("<i class='fa fa-arrows-h'></i>");
                    $(this).parents('.wz-wrap-table').find('table').css('word-break', 'keep-all')
                } else {
                    $(this).data('status', 'open');
                    $(this).html("<i class='fa fa-arrows-v'></i>");
                    $(this).parents('.wz-wrap-table').find('table').css('word-break', 'normal');
                }
            });
        }, 0);

        window.setTimeout(function () {
            // 图片缩放支持
            $.wz.imageResize('#markdown-body');
        }, 0);
    });
</script>
@endpush