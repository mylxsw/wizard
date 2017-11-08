@can('page-edit', $pageItem)
<li role="presentation" class="dropdown">
    <a class="dropdown-toggle" data-toggle="dropdown" href="#" role="button"
       aria-haspopup="true" aria-expanded="false">
        <span class="glyphicon glyphicon-option-horizontal" title="@lang('common.btn_more')"></span>
    </a>
    <ul class="dropdown-menu wz-dropdown-menu-left">
        <li><a href="{{ wzRoute('project:doc:attachment', ['id' => $project->id, 'page_id' => $pageItem->id]) }}">
                <span class="glyphicon glyphicon-paperclip"></span>
                附件
            </a></li>
        <li><a href="#" wz-share data-url="{{ wzRoute('project:doc:share', ['id' => $project->id, 'page_id' => $pageItem->id]) }}">
                <span class="glyphicon glyphicon-share"></span>
                @lang('common.btn_share')
            </a></li>
        @if($pageItem->type == \App\Repositories\Document::TYPE_DOC)

            <li>
                <form id="form-{{ $pageItem->id }}-export-pdf" method="post"
                      action="{{ route('project:doc:pdf', ['id' => $project->id, 'page_id' => $pageItem->id]) }}">
                    {{ csrf_field() }}
                </form>
                <a wz-form-submit href="#" data-form="#form-{{ $pageItem->id }}-export-pdf">
                <span class="glyphicon glyphicon-export"></span>
                导出PDF
            </a></li>
        @endif
        <li>
            <a href="{{ wzRoute('project:doc:history', ['id' => $project->id, 'page_id' => $pageItem->id ]) }}">
                <span class="glyphicon glyphicon-compressed"></span>
                @lang('document.page_history')
            </a>
        </li>
        <li>
            <form id="form-{{ $pageItem->id }}" method="post"
                  action="{{ wzRoute('project:doc:delete', ['id' => $project->id, 'page_id' => $pageItem->id]) }}">
                {{ method_field('DELETE') }}{{ csrf_field() }}
            </form>
            <a href="#" wz-form-submit data-form="#form-{{ $pageItem->id }}"
               data-confirm="@lang('document.delete_confirm', ['title' => $pageItem->title])">
                <span class="glyphicon glyphicon-trash"></span>
                @lang('common.btn_delete')
            </a>
        </li>
    </ul>
</li>
@endcan
@push('script')
<script>
    $(function() {
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
    });
</script>
@endpush
