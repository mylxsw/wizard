
<li role="presentation" class="mr-2">
    <button type="button" data-toggle="modal" data-target="#wz-export" title="导出文件" class="btn btn-primary bmd-btn-icon" id="wz-export-trigger">
        <span class="fa fa-download" data-toggle="tooltip" title="导出为"></span>
    </button>
</li>

<div class="modal fade" id="wz-export" tabindex="-1" role="dialog" aria-labelledby="wz-export">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">导出为</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                @if($pageItem->type == \App\Repositories\Document::TYPE_DOC)
                <a href="#" class="dropdown-item wz-export-pdf">
                    <span class="fa fa-download mr-2"></span>
                    PDF
                </a>
                <a href="#" class="dropdown-item wz-export-markdown">
                    <span class="fa fa-download mr-2"></span>
                    Markdown
                </a>
                @endif

                @if($pageItem->type == \App\Repositories\Document::TYPE_SWAGGER)
                <a href="#" class="dropdown-item wz-export-swagger"
                   data-data-url="{!! wzRoute('swagger:doc:json', ['id' => $project->id, 'page_id' => $pageItem->id, 'ts' => microtime(true)])  !!}"
                   data-download-url="{!! wzRoute('export:download', ['filename' => "{$pageItem->title}.json"]) !!}">
                    <span class="fa fa-download mr-2"></span>
                    JSON
                </a>
                <a href="#" class="dropdown-item wz-export-swagger"
                   data-data-url="{!! wzRoute('swagger:doc:yml', ['id' => $project->id, 'page_id' => $pageItem->id, 'ts' => microtime(true)])  !!}"
                   data-download-url="{!! wzRoute('export:download', ['filename' => "{$pageItem->title}.yml"]) !!}">
                    <span class="fa fa-download mr-2"></span>
                    YAML
                </a>
                @endif

            </div>
        </div>
    </div>
</div>


@push('script')
<script src="/assets/vendor/html2canvas.min.js"></script>
<script>
    $(function () {

    @if($pageItem->type == \App\Repositories\Document::TYPE_DOC)
        // PDF 导出
        $('.wz-export-pdf').on('click', function (e) {
            e.preventDefault();

            var index = layer.msg('预处理中...', {
                icon: 16, shade: 0.01, time: 30000
            });

            setTimeout(function() {
                // 检查是否是黑暗模式，黑暗模式需要先切换为正常模式
                // 解决黑暗模式下公式为白色的问题
                var body = $('body');
                var isDarkTheme = false;
                if (body.hasClass('wz-dark-theme')) {
                    body.removeClass('wz-dark-theme');
                    isDarkTheme = true;
                }

                Promise.all($('#markdown-body').find('.editormd-tex, pre.prettyprint, .flowchart, .sequence-diagram, .mermaid').map(function() {
                    var self = $(this);
                    return html2canvas(self[0]).then(function(canvas) {
                        var image = new Image();
                        image.src = canvas.toDataURL("image/png");
                        self.replaceWith(image);
                    });
                })).then(function() {
                    layer.close(index);
                    if (isDarkTheme) {
                        body.addClass('wz-dark-theme');
                    }

                    layer.msg('努力渲染中...', {
                        icon: 16, shade: 0.01, time: 50000
                    });

                    var contentBody = $('#markdown-body').clone();
                    contentBody.find('textarea').remove();

                    $.wz.dynamicFormSubmit(
                        'generate-pdf-{{ $pageItem->id }}',
                        'POST',
                        '{{ wzRoute('export:pdf', ['type' => documentType($pageItem->type)]) }}',
                        {
                            "html": contentBody.html(),
                            "title": "{{ $pageItem->title }}"
                        }
                    );
                });
            }, 100);
        });

        // 普通导出
        $('.wz-export-markdown').on('click', function (e) {
            e.preventDefault();

            $.wz.dynamicFormSubmit(
                'generate-markdown-{{ $pageItem->id }}',
                'POST',
                '{{ wzRoute('export:download', ['filename' => "{$pageItem->title}.md"]) }}',
                {
                    content: $('.wz-markdown-content').val(),
                }
        )
        });
    @endif

    @if($pageItem->type == \App\Repositories\Document::TYPE_SWAGGER)
        $('.wz-export-swagger').on('click', function (e) {
            e.preventDefault();

            var data_url = $(this).data('data-url');
            var download_url = $(this).data('download-url');

            $.get(data_url, {}, function (data) {
                $.wz.dynamicFormSubmit(
                    'generate-swagger-{{ $pageItem->id }}',
                    'POST',
                    download_url,
                    {
                        content: data,
                    }
                );
            }, 'text');
        });
    @endif
    });
</script>
@endpush
