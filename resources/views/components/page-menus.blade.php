@can('page-edit', $pageItem)
<li role="presentation" class="dropdown">
    <a class="dropdown-toggle" data-toggle="dropdown" href="#" role="button"
       aria-haspopup="true" aria-expanded="false">
        @lang('common.btn_more') <span class="caret"></span>
    </a>
    <ul class="dropdown-menu">
        <li><a href="#" wz-share data-url="{{ wzRoute('project:doc:share', ['id' => $project->id, 'page_id' => $pageItem->id]) }}">@lang('common.btn_share')</a></li>
        <li><a href="#" wz-wait-develop>@lang('common.btn_export')</a></li>
        <li>
            <a href="{{ wzRoute('project:doc:history', ['id' => $project->id, 'page_id' => $pageItem->id ]) }}">@lang('document.page_history')</a>
        </li>
        <li>
            <form id="form-{{ $pageItem->id }}" method="post"
                  action="{{ wzRoute('project:doc:delete', ['id' => $project->id, 'page_id' => $pageItem->id]) }}">
                {{ method_field('DELETE') }}{{ csrf_field() }}
            </form>
            <a href="#" wz-form-submit data-form="#form-{{ $pageItem->id }}"
               data-confirm="@lang('document.delete_confirm', ['title' => $pageItem->title])">@lang('common.btn_delete')</a>
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
