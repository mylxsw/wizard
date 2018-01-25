@extends('layouts.default')
@section('title', $project->name)
@section('container-style', 'container-fluid')
@section('content')

    <div class="row marketing wz-main-container-full">
        @include('components.error', ['error' => $errors ?? null])
        <form style="width: 100%;" method="POST" id="wz-doc-edit-form"
              action="{{ $newPage ? wzRoute('project:doc:new:show', ['id' => $project->id]) : wzRoute('project:doc:edit:show', ['id' => $project->id, 'page_id' => $pageItem->id]) }}">

            @include('components.doc-edit', ['project' => $project, 'pageItem' => $pageItem ?? null, 'navigator' => $navigator])
            <input type="hidden" name="type" value="swagger" />

            <div class="col-row mb-5">
                <div class="swagger-editor-toolbar">
                    <div class="btn-toolbar" role="toolbar" aria-label="Toolbar with button groups">
                        <div class="btn-group" role="group" aria-label="First group">
                            <button type="button" data-toggle="modal" data-target="#wz-select-template" class="btn btn-info btn-raised">@lang('document.select_template')</button>
                        </div>
                    </div>
                </div>
                <div id="editor-content"></div>
            </div>
        </form>
    </div>
@endsection

@push('bottom')
    <div class="modal fade" id="wz-select-template" tabindex="-1" role="dialog" aria-labelledby="wz-select-template">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@lang('document.select_template')</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    @foreach(wzTemplates(\App\Repositories\Template::TYPE_SWAGGER) as $temp)
                        <div class="radio">
                            <label>
                                <input type="radio" name="template" value="{{ $temp['id'] }}"
                                       data-content="{{ base64_encode($temp['content']) }}" {{ $temp['default'] ? 'checked' : '' }}>
                                <span title="{{ $temp['description'] }}"> {{ $temp['name'] }}</span>
                                @if($temp['scope'] == \App\Repositories\Template::SCOPE_PRIVATE)
                                    <span class="glyphicon glyphicon-eye-close" title="@lang('project.privilege_private')"></span>
                                @endif
                            </label>
                        </div>
                    @endforeach
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-success btn-raised" id="wz-select-template-confirm">@lang('common.btn_confirm')</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">@lang('common.btn_close')</button>
                </div>
            </div>
        </div>
    </div>
@endpush

@push('stylesheet')
    <link href="/assets/vendor/swagger-editor/swagger-editor.css" rel="stylesheet">
    <style>
        #editor-wrapper {
            height: 100%;
            border:none;
        }
        #editor-content {
            height: 640px;
        }
        .Pane2 {
            overflow-y: scroll;
        }

        .errors__clear-btn {
            display: none;
        }
    </style>
@endpush

@push('script')
    <script src="/assets/vendor/base64.min.js"></script>
    <script src="/assets/vendor/swagger-editor/swagger-editor-bundle.js"></script>
    <script src="/assets/vendor/swagger-editor/swagger-editor-standalone-preset.js"></script>

    <script>
        $(function() {
            // 获取swagger编辑器中的内容
            $.global.getEditorContent = function () {
                return window.editor.specSelectors.specStr();
            };

            // 用于表单提交后回调，清理缓存内容
            $.global.clearDocumentDraft = function () {
                $.global.updateSwaggerDraft('');
            };

            // 获取swagger编辑器本次存储内容的key
            $.global.getSwaggerDraftKey = function() {
                return 'swagger-editor-content-{{ $project->id or '' }}-{{ $pageItem->id or '' }}';
            };

            // 获取swagger编辑器本地存储内容（未保存的内容）
            $.global.updateSwaggerFromDraft = function (original, updateSwaggerContent) {
                window.setTimeout(function () {
                    if ($.global.getEditorContent() !== original) {
                        $.wz.confirm('@lang('document.draft_continue_edit_confirm')', function () {
                            updateSwaggerContent(original);
                        }, function () {
                            $.global.updateSwaggerDraft('');
                        });
                    }
                }, 0);
            };

            window.editor = SwaggerEditorBundle({
                dom_id: '#editor-content'
                ,layout: 'EditorLayout'
                ,presets: [
                    SwaggerEditorStandalonePreset
                ]
                @if(!empty($pageItem))
                ,url: "{{ wzRoute('project:doc:json', ['id' => $project->id, 'page_id' => $pageItem->id, 'only_body' => 1]) }}"
                @else
                ,url: ""
                @endif
            });

            // 选择模板对话框
            $('#wz-select-template-confirm').on('click', function() {
                var templateSelector = $("#wz-select-template");
                var template = templateSelector.find("input[name=template]:checked");
                if (template.length === 0) {
                    templateSelector.modal('hide');
                    return ;
                }

                window.editor.specActions.updateSpec(Base64.decode(template.data('content')));
                templateSelector.modal('hide');
            });
        });
    </script>
@endpush
