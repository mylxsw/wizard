@if (!Auth::guest())
<li role="presentation" class="dropdown">
    <button class="btn bmd-btn-icon dropdown-toggle" type="button" id="wz-doc-more-btn" data-toggle="dropdown"
            aria-haspopup="true" aria-expanded="false">
        <i class="material-icons" title="@lang('common.btn_more')">tune</i>
    </button>

    <div class="dropdown-menu wz-dropdown-menu-left" aria-labelledby="wz-doc-more-btn">

        <a href="{{ wzRoute('project:doc:history', ['id' => $project->id, 'page_id' => $pageItem->id ]) }}"
           class="dropdown-item">
            <span class="fa fa-history mr-2"></span>
            @lang('document.page_history')
        </a>

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
@endif

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
        });
    </script>
@endpush
