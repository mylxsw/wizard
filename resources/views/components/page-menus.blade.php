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
                <a href="#" wz-move
                   class="dropdown-item" data-toggle="modal" data-target="#wz-move-model">
                    <span class="fa fa-copy mr-2"></span>
                    移动
                </a>
                <a href="#" wz-share
                   data-url="{{ wzRoute('project:doc:share', ['id' => $project->id, 'page_id' => $pageItem->id]) }}"
                   class="dropdown-item">
                    <span class="fa fa-share-alt mr-2"></span>
                    @lang('common.btn_share')
                </a>

                <a href="#" wz-form-submit data-form="#form-outdated-{{ $pageItem->id }}"
                   data-confirm="确定要将文档标记为 {{ $pageItem->status == \App\Repositories\Document::STATUS_NORMAL ? '已过时' : '正常' }}" class="dropdown-item">
                    <span class="fa fa-outdent mr-2"></span>
                    {{ $pageItem->status == \App\Repositories\Document::STATUS_NORMAL ? '标记' : '取消' }}<del style="font-style: oblique">已过时</del>
                    <form id="form-outdated-{{ $pageItem->id }}" method="post"
                          action="{{ wzRoute('project:doc:mark-status', ['id' => $project->id, 'page_id' => $pageItem->id]) }}">
                        {{ method_field('PUT') }}{{ csrf_field() }}
                        <input type="hidden" name="status" value="{{ $pageItem->status == \App\Repositories\Document::STATUS_NORMAL ? \App\Repositories\Document::STATUS_OUTDATED : \App\Repositories\Document::STATUS_NORMAL }}">
                    </form>
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
    @can('page-edit', $pageItem)
        <div class="modal fade" id="wz-move-model" tabindex="-1" role="dialog" aria-labelledby="wz-move-model">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">移动到</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">

                        <div class="form-group wz-document-form-select">
                            <label for="wz-target-project_id" class="bmd-label-static">项目</label>
                            <select class="form-control" name="target_project_id" id="wz-target-project_id"></select>
                        </div>

                        <div class="form-group wz-document-form-select">
                            <label for="wz-target-page_id" class="bmd-label-static">目录</label>
                            <select class="form-control" name="target_page_id" id="wz-target-page_id"></select>
                        </div>

                        <button class="btn btn-raised btn-info" wz-move-confirm>确定</button>
                    </div>
                </div>
            </div>
        </div>
    @endif
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

            $('#wz-move-model').on('show.bs.modal', function (event) {
                var modal = $(this);
                $.wz.request('get', '/user/writable-projects', {}, function (data) {
                    modal.find('#wz-target-project_id').html("<option value='0' selected>选择项目</option>" + data.map(function (item) {
                        return "<option value='" + item.id + "'>" + (item.catalog_name != null ? item.catalog_name + " ▶ " : "") + item.name + "</option>";
                    }).join(''));
                });
            });

            $('#wz-target-project_id').on('change', function () {
                $('#wz-target-page_id').html('');

                var project_id = $('#wz-target-project_id').val();
                if (project_id === '' || project_id === '0') {
                    return;
                }

                $.wz.request('get', '/project/' + project_id + "/doc-selector", {exclude_page_id: {{ $pageItem->id }} }, function (data) {
                    $('#wz-target-page_id').html(data);
                }, null, 'html');
            });

            $('button[wz-move-confirm]').on('click', function (e) {
                e.preventDefault();

                var targetProjectId = $('#wz-target-project_id').val();
                var targetPageId = $('#wz-target-page_id').val();

                if (targetProjectId === '' || targetProjectId === '0') {
                    return;
                }

                layer.load(3, 3000);

                $.wz.dynamicFormSubmit(
                    'move-document-{{ $project->id }}',
                    'POST',
                    '{{ wzRoute('project:move', ['project_id' => $project->id, 'page_id' => $pageItem->id]) }}',
                    {
                        target_project_id: targetProjectId,
                        target_page_id: targetPageId,
                    }
                )
            });
            @endif
        });
    </script>
@endpush
