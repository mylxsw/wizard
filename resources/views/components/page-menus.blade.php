
<li role="presentation" class="dropdown">
    <button class="btn bmd-btn-icon dropdown-toggle" type="button" id="wz-doc-more-btn" data-toggle="dropdown"
            aria-haspopup="true" aria-expanded="false">
        <i class="material-icons" title="@lang('common.btn_more')">tune</i>
    </button>

    <div class="dropdown-menu wz-dropdown-menu-left" aria-labelledby="wz-doc-more-btn">
        <a class="dropdown-item" href="#"
           data-toggle="modal" data-target="#wz-export">
            <span class="fa fa-download mr-2"></span>
            导出文件
        </a>
        @if (!Auth::guest())
            <a href="{{ wzRoute('project:doc:history', ['id' => $project->id, 'page_id' => $pageItem->id ]) }}"
               class="dropdown-item">
                <span class="fa fa-history mr-2"></span>
                @lang('document.page_history')
            </a>
        @endif
        @can('page-edit', $pageItem)
            <a href="#" wz-share
               data-url="{{ wzRoute('project:doc:share', ['id' => $project->id, 'page_id' => $pageItem->id]) }}"
               class="dropdown-item">
                <span class="fa fa-share-alt mr-2"></span>
                @lang('common.btn_share')
            </a>

            <a href="#" wz-form-submit data-form="#form-{{ $pageItem->id }}"
               data-confirm="@lang('document.delete_confirm', ['title' => $pageItem->title])" class="dropdown-item">
                <span class="fa fa-trash mr-2"></span>
                @lang('common.btn_delete')
                <form id="form-{{ $pageItem->id }}" method="post"
                      action="{{ wzRoute('project:doc:delete', ['id' => $project->id, 'page_id' => $pageItem->id]) }}">
                    {{ method_field('DELETE') }}{{ csrf_field() }}
                </form>
            </a>
        @endif

    </div>
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

            </div>
        </div>
    </div>
</div>

@push('script')
    <script>
        $(function () {
            @can('page-edit', $pageItem)
            $('a[wz-share]').on('click', function (e) {
                e.preventDefault();

                var $this = $(this);

                $.wz.confirm('确定要为该文档创建分享链接？', function () {
                    var url = $this.data('url');

                    $.wz.request('post', url, {}, function (data) {
                        $.wz.alert(
                            '分享链接地址为: <br /><a target="_blank" href="' + $.wz.url(data.link) + '">' + $.wz.url(data.link) + '</a>'
                        );
                    });
                });
            });
            @endif

            @if($pageItem->type == \App\Repositories\Document::TYPE_DOC)
            // PDF 导出
            $('.wz-export-pdf').on('click', function (e) {
                e.preventDefault();

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
                )
            });

            // 普通导出
            $('.wz-export-markdown').on('click', function (e) {
                e.preventDefault();

                $.wz.dynamicFormSubmit(
                    'generate-markdown',
                    'POST',
                    '{{ wzRoute('export:download', ['filename' => "{$pageItem->title}.md"]) }}',
                    {
                        content: $('.wz-markdown-content').val(),
                    }
                )
            });
            @endif
        });
    </script>
@endpush
