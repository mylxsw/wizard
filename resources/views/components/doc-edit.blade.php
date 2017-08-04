{{ csrf_field() }}
<input type="hidden" name="project_id" id="editor-project_id" value="{{ $project->id or '' }}"/>
<input type="hidden" name="page_id" id="editor-page_id" value="{{ $pageItem->id or '' }}">
<input type="hidden" name="pid" id="editor-pid" value="{{ $pageItem->pid or '' }}">
<input type="hidden" name="last_modified_at" value="{{ $pageItem->updated_at or '' }}">
<input type="hidden" name="history_id" value="{{ $pageItem->history_id or '' }}">
<div class="col-lg-12 wz-edit-control">

    <div class="form-group input-group">
        <span class="input-group-addon" title="@lang('project.project_name')">{{ $project->name }}</span>
        <input type="text" class="form-control wz-input-long" name="title" id="editor-title"
               value="{{ $pageItem->title or '' }}" placeholder="@lang('document.title')">
    </div>

    <div class="form-group">
        <select class="form-control" name="pid">
            <option value="0">@lang('document.no_parent_page')</option>
            @include('components.doc-options', ['navbars' => $navigator, 'level' => 0])
        </select>
    </div>

    <div class="form-group pull-right">
        <div class="btn-group">
            <button type="button" class="btn btn-success" wz-doc-form-submit id="wz-doc-form-submit">@lang('common.btn_save')</button>
            <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown"
                    aria-haspopup="true" aria-expanded="false">
                <span class="caret"></span>
            </button>
            <ul class="dropdown-menu">
                <li><a href="#" wz-doc-save-template>@lang('document.save_as_template')</a></li>
                <li><a href="#">@lang('document.save_as_draft')</a></li>
                @if(!$newPage)
                    <li><a href="#" wz-doc-form-submit data-force="true">@lang('document.force_save')</a></li>
                    <li><a href="#" wz-doc-compare-current>@lang('document.show_diff')</a></li>
                @endif
            </ul>
        </div>
        <a href="{{ wzRoute('project:home', ['id' => $project->id] + (empty($pageItem) ? [] : ['p' => $pageItem->id])) }}" class="btn btn-default">@lang('common.btn_back')</a>
    </div>
</div>

@push('script')
<script>
$(function() {
    // 文档保存
    $('[wz-doc-form-submit]').on('click', function () {
        var force = $(this).data('force');
        var form = $(this).parents('form');

        var formSubmit = function (form, force) {
            $.wz.asyncForm(form, {
                force: force ? 1 : 0
            }, function (data) {
                $.wz.alert(data.message, function () {
                    window.location.href = data.redirect;
                });
            });
        };

        if (force) {
            $.wz.confirm('@lang('document.force_save_confirm')', function () {
                formSubmit(form, true);
            });
        } else {
            $.wz.confirm('@lang('document.save_confirm')', function () {
                formSubmit(form, false);
            });
        }
    });

    // 保存为模板
    $('[wz-doc-save-template]').on('click', function (e) {
        e.preventDefault();

        alert("Hello");
    });

    @if(!$newPage)

        // 文档差异对比
        $('[wz-doc-compare-current]').on('click', function(e) {
            e.preventDefault();

            var compareUrl = '{{ wzRoute('doc:compare') }}';
            var docUrl = '{{ wzRoute('project:doc:json', ['id' => $project->id, 'page_id' => $pageItem->id]) }}';

            axios.get(docUrl).then(function (resp) {
                var layerId = 'wz-frame-' + (new Date()).getTime();

                $.wz.dialogOpen(layerId, '@lang('document.document_differ')', function (iframeId) {
                    $.wz.dynamicFormSubmit(
                        'wz-compare-' + resp.data.id,
                        'post',
                        compareUrl,
                        {
                            doc1: $.global.getEditorContent(),
                            doc2: resp.data.content,
                            doc1title: '@lang('document.after_modified')',
                            doc2title: '@lang('document.latest_document')',
                            noheader: 1
                        },
                        iframeId
                    );
                });
            });
        });

        // 自动检查文档是否过期
        (function() {
            var lastModifiedAt = $('input[name=last_modified_at]').val();
            var checkExpiredURL = '{{ route('project:doc:expired', ['id' => $project->id, 'page_id' => $pageItem->id]) }}';
            var continueCheck = function () {
                window.setTimeout(function () {
                    $.wz.request('get', checkExpiredURL, {l:lastModifiedAt}, function (data) {
                        // 没有过期则继续检查
                        if (!data.expired) {
                            continueCheck();
                            return false;
                        }

                        // 已过期，禁用保存按钮，同时页面提示
                        $('#wz-doc-form-submit').prop('disabled', 'disabled');
                        $('#wz-error-box').fadeIn('fast').html(data.message);

                    }, continueCheck);
                }, 5000);

                return true;
            };

            continueCheck();
        })();
    @endif
});
</script>
@endpush