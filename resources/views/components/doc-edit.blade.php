<div class="row wz-editor-header">
    {{ csrf_field() }}
    <input type="hidden" name="project_id" id="editor-project_id" value="{{ $project->id ?? '' }}"/>
    <input type="hidden" name="page_id" id="editor-page_id" value="{{ $pageItem->id ?? '' }}">
    <input type="hidden" name="pid" id="editor-pid" value="{{ $pageItem->pid ?? '' }}">
    <input type="hidden" name="last_modified_at" value="{{ $pageItem->updated_at ?? '' }}">
    <input type="hidden" name="history_id" value="{{ $pageItem->history_id ?? '' }}">
    <input type="hidden" name="sort_level" value="{{ $pageItem->sort_level ?? 1000 }}">
    <div class="col-12 wz-editor-header-toolbar">
        <div class="wz-panel-breadcrumb">
            <ol class="breadcrumb pull-left">
                <li class="breadcrumb-item"><a href="{{ wzRoute('home') }}">首页</a></li>
                @if(!empty($project->catalog))
                    <li class="breadcrumb-item"><a href="{{ wzRoute('home', ['catalog' => $project->catalog->id]) }}">{{ $project->catalog->name }}</a></li>
                @endif
                <li class="breadcrumb-item"><a href="{{ wzRoute('project:home', ['id' => $project->id]) }}">{{ $project->name }}</a></li>
                @if(!empty($pageItem->title))
                    <li class="breadcrumb-item active">{{ $pageItem->title }}</li>
                @endif
            </ol>
            <ul class="nav nav-pills pull-right">
                <li role="presentation" class="mr-2">
                    <button type="button"
                            data-href="{{ wzRoute('project:home', ['id' => $project->id] + (empty($pageItem) ? [] : ['p' => $pageItem->id])) }}"
                            class="btn btn-default bmd-btn-icon" id="wz-document-goback">
                        <i class="material-icons">clear</i>
                    </button>
                </li>
            </ul>
            <div class="clearfix"></div>
        </div>
    </div>
    <div class="col wz-edit-control">

        <div class="form-group pull-right wz-edit-btn-group">
            <button type="button" class="btn btn-raised btn-primary mr-3" wz-doc-form-submit id="wz-doc-form-submit">
                <i class="fa fa-save mr-1"></i> 保存
            </button>
            <button class="btn  dropdown-toggle" type="button" id="form-save-extra-menu" data-toggle="dropdown"
                    aria-haspopup="true" aria-expanded="false">
                更多
            </button>
            <div class="dropdown-menu" aria-labelledby="form-save-extra-menu" style="min-width: 12rem;">
                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#wz-new-template">
                    <i class="fa fa-th mr-2"></i>
                    @lang('document.save_as_template')
                </a>
                @if(!$newPage)
                    <a class="dropdown-item" href="#" wz-doc-form-submit data-force="true">
                        <i class="fa fa-save mr-2"></i> @lang('document.force_save')
                    </a>
                    <a class="dropdown-item" href="#" wz-doc-compare-current>
                        <i class="fa fa-adjust mr-2"></i> @lang('document.show_diff')
                    </a>
                @endif
            </div>
        </div>
        <div class="pull-left wz-document-form">
            <div class="form-group wz-document-form-select">
                <label for="form-pid" class="bmd-label-static">上级页面</label>
                <select class="form-control" name="pid" id="form-pid">
                    <option value="0">@lang('document.no_parent_page')</option>
                    @include('components.doc-options', ['navbars' => $navigator, 'level' => 0])
                </select>
            </div>

            <div class="form-group">
                <label for="editor-title" class="bmd-label-static">@lang('document.title')</label>
                <input type="text" class="form-control wz-input-long" name="title" id="editor-title"
                       value="{{ $pageItem->title ?? '' }}">
            </div>

            @if($type === 'swagger')
                <div class="form-group">
                    <label for="form-sync-url" class="bmd-label-static">文档同步地址</label>
                    <input type="text" class="form-control wz-input-long" name="sync_url" id="editor-sync_url" value="{{ $pageItem->sync_url ?? '' }}" placeholder="http://"/>
                </div>
            @endif
        </div>

    </div>
</div>

@include('components.error', ['error' => $errors ?? null])

@push('bottom')
    <div class="modal fade" id="wz-new-template" tabindex="-1" role="dialog" aria-labelledby="wz-new-template">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@lang('document.save_as_template')</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <form method="post" action="{{ wzRoute('template:create') }}" id="wz-template-save-form">
                        {{ csrf_field() }}
                        <input type="hidden" name="type" value="{{ $type ?? 'markdown' }}"/>
                        <div class="form-group">
                            <label for="template-name" class="control-label">@lang('document.template_name')</label>
                            <input type="text" name="name" class="form-control" id="template-name">
                        </div>
                        <div class="form-group">
                            <label for="template-description" class="control-label">
                                @lang('document.template_description')
                            </label>
                            <textarea class="form-control" name="description" id="template-description"></textarea>
                        </div>
                        @can('template-global-create')
                            <div class="form-group">
                                <div class="">
                                    <label>
                                        <input type="checkbox" name="scope" value="1">
                                        @lang('document.template_global_access')
                                    </label>
                                </div>
                            </div>
                        @endcan
                    </form>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-success btn-raised mr-3" id="wz-template-save">
                        @lang('common.btn_save')
                    </button>
                    <button type="button" class="btn btn-default"
                            data-dismiss="modal">@lang('common.btn_close')</button>
                </div>
            </div>
        </div>
    </div>
@endpush

@push('script')
    <script>
        $(function () {
            // 获取本地草稿内容
            var getDraftContent = function () {
                return store.get($.global.getDraftKey()) || '';
            };

            var autoSaveDraftTimer;

            // 自动保存草稿
            var autoSaveDraft = function () {
                var currentContent = $.global.getEditorContent();
                var savedContent = getDraftContent();

                if (savedContent !== currentContent) {
                    store.set($.global.getDraftKey(), currentContent);
                    if (savedContent !== '') {
                        $.wz.toast('本地草稿已保存');
                    }
                }

                autoSaveDraftTimer = setTimeout(autoSaveDraft, 3000);
            };

            // 文档保存
            var documentSaving = false;
            $('[wz-doc-form-submit]').on('click', function () {

                if (documentSaving) {
                    return false;
                }
                documentSaving = true;

                var force = $(this).data('force');
                var form = $(this).parents('form');

                $.wz.btnAutoLock($(this));

                var formSubmit = function (form, force) {
                    $.wz.asyncForm(form, {
                        force: force ? 1 : 0,
                        content: $.global.getEditorContent()
                    }, function (data) {

                        // 清除文档过期检查任务
                        if (typeof window.document_check_task !== 'undefined') {
                            window.clearTimeout(window.document_check_task);
                        }

                        $.global.clearDocumentDraft();

                        layer.confirm('保存成功，是否继续编辑本文档？', {
                            icon: 1,
                            closeBtn: 0,
                            btn: ['继续编辑', '新文档', '查看'],
                            btn3: function () {
                                window.location.href = data.redirect.show;
                            }
                        }, function (index) {
                            window.location.href = data.redirect.edit;
                            // layer.close(index);
                            // documentSaving = false;
                        }, function () {
                            window.location.href = '{!! wzRoute('project:doc:new:show', ['id' => $project->id, 'type' => $type, 'pid' => $pid]) !!}';
                        });
                    }, function() {
                        setTimeout(function(){
                            documentSaving = false;
                        }, 3000);
                    });
                };

                if (force) {
                    $.wz.confirm('@lang('document.force_save_confirm')', function () {
                        formSubmit(form, true);
                    }, function () {
                        documentSaving = false;
                    });
                } else {
                    formSubmit(form, false);
                }
            });

            // 快捷键保存
            document.addEventListener('keydown', function (e) {
                if (e.key === "s" && (e.ctrlKey||e.metaKey)) {
                    e.preventDefault();
                    if (!documentSaving) {
                        $('#wz-doc-form-submit').trigger('click');
                    }
                }
            });

            // 禁止标题表单回车自动提交
            // 解决标题输入表单中，按下回车时自动提交表单，直接展示为json页面的问题
            $('.wz-document-form input').keydown(function (event) {
                if (event.keyCode === 13) {
                    event.preventDefault();
                }
            });

            // 另存为模板
            $('#wz-template-save').on('click', function () {
                $.wz.btnAutoLock($(this));
                var form = $('#wz-template-save-form');
                $.wz.asyncForm(form, {content: $.global.getEditorContent()}, function (data) {
                    $.wz.message_success('@lang('common.operation_success')', function () {
                        $('#wz-new-template').modal('hide');
                    });
                });
            });

            $.global.clearDocumentDraft = function () {
                // 停止自动保存草稿功能
                window.clearTimeout(autoSaveDraftTimer);
                store.remove($.global.getDraftKey());
            };


            // 检查草稿是否存在
            // 存在询问是否恢复，之后启动自动保存功能
            setTimeout(function () {
                var draftContent = getDraftContent();
                // 只有大于20个字符的文档才恢复
                if (draftContent.length > 20 && $.global.getEditorContent() !== draftContent) {
                    $.wz.confirm('@lang('document.draft_continue_edit_confirm')', function () {
                        $.global.updateEditorContent(draftContent);
                        autoSaveDraft();
                    }, function () {
                        store.remove($.global.getDraftKey());
                        autoSaveDraft();
                    });
                } else {
                    autoSaveDraft();
                }
            }, 1000);


            @if(!$newPage)

            // 文档差异对比
            $('[wz-doc-compare-current]').on('click', function (e) {
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
                                doc1title: $('#editor-title').val(),
                                doc2title: resp.data.title,
                                doc1pid: $('#form-pid').val(),
                                doc2pid: resp.data.pid,
                                noheader: 1
                            },
                            iframeId
                        );
                    });
                });
            });

            // 自动检查文档是否过期
            (function () {
                var lastModifiedAt = $('input[name=last_modified_at]').val();
                var checkExpiredURL = '{{ wzRoute('project:doc:expired', ['id' => $project->id, 'page_id' => $pageItem->id]) }}';
                var continueCheck = function () {
                    window.document_check_task = window.setTimeout(function () {
                        $.wz.request('get', checkExpiredURL, {l: lastModifiedAt}, function (data) {
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