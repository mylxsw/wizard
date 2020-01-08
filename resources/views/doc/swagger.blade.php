@extends('layouts.default')
@section('title', $project->name)
@section('container-style', 'container-fluid')
@section('content')

    <div class="row marketing wz-main-container-full">
        <form style="width: 100%;" method="POST" id="wz-doc-edit-form"
              action="{{ $newPage ? wzRoute('project:doc:new:show', ['id' => $project->id]) : wzRoute('project:doc:edit:show', ['id' => $project->id, 'page_id' => $pageItem->id]) }}">

            @include('components.doc-edit', ['project' => $project, 'pageItem' => $pageItem ?? null, 'navigator' => $navigator])
            <input type="hidden" name="type" value="swagger" />

            <div class="col-row swagger-editor-panel">
                <div class="swagger-editor-toolbar">
                    <div class="btn-toolbar btn-toolbar-left" role="toolbar" aria-label="Toolbar with button groups">
                        <div class="btn-group" role="group" aria-label="First group">
                            <button type="button" data-toggle="modal" data-target="#wz-select-template" class="btn btn-info btn-raised">@lang('document.select_template')</button>
                        </div>
                        <div class="btn-group">
                            <a href="https://swagger.io/docs/specification/about/" target="_blank" class="btn btn-dark">
                                <i class="fa fa-question-circle"></i>
                            </a>
                        </div>
                    </div>
                    <div class="btn-toolbar btn-toolbar-right" role="toolbar" aria-label="Toolbar with button groups">
                        <div class="btn-group">
                            <button type="button" class="btn btn-dark wz-fullscreen-edit"><i class="fa fa-arrows-alt"></i></button>
                        </div>
                    </div>
                </div>
                <div class="swagger-editor-body">
                    <div id="editor-content" class="swagger-editor-content"></div>
                </div>
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
                <div class="modal-body wz-swagger-template">
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
                    <button type="button" class="btn btn-success btn-raised mr-2" id="wz-select-template-confirm">@lang('common.btn_confirm')</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">@lang('common.btn_close')</button>
                </div>
            </div>
        </div>
    </div>
@endpush

@push('stylesheet')
    <link href="{{ cdn_resource('/assets/vendor/swagger-editor/swagger-editor.css') }}" rel="stylesheet">
    <style>
        #editor-wrapper {
            height: 100%;
            border:none;
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
    <script src="{{ cdn_resource('/assets/vendor/base64.min.js') }}"></script>
    <script src="{{ cdn_resource('/assets/vendor/swagger-editor/swagger-editor-bundle.js') }}"></script>
    <script src="{{ cdn_resource('/assets/vendor/swagger-editor/swagger-editor-standalone-preset.js') }}"></script>

    <script>
        $(function() {
            // 获取swagger编辑器中的内容
            $.global.getEditorContent = function () {
                return window.editor.specSelectors.specStr();
            };

            // 获取swagger编辑器本次存储内容的key
            $.global.getDraftKey = function() {
                return 'swagger-editor-content-{{ $project->id ?? '' }}-{{ $pageItem->id ?? '' }}';
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

            // 更新编辑器内容
            $.global.updateEditorContent = function (content) {
                window.editor.specActions.updateSpec(content);
            };

            // 动态调整swagger编辑器高度
            $.global.windowResize = function () {
                // var editorHeight = $('.wz-body').height() - ($('.wz-editor-header').height() + $('.swagger-editor-toolbar').height());
                // $('.swagger-editor-body, .swagger-editor-content').height(editorHeight);
                $('.swagger-editor-body, .swagger-editor-content').height($(window).height() - $('.swagger-editor-toolbar').height());
                // 用于解决swagger编辑器初始时无法展示出所有文档代码的问题
                $('.swagger-editor-content .ace_content').trigger('click');
            };

            // 全屏幕编辑支持
            $('.wz-fullscreen-edit').on('click', function () {
                var editor_panel = $('.swagger-editor-panel');
                editor_panel.toggleClass('swagger-fullscreen');
                if (editor_panel.hasClass('swagger-fullscreen')) {
                    $('.wz-body').css('min-height', 'auto');
                    $(this).html('<i class="fa fa-compress"></i>');
                } else {
                    $('.wz-body').css('min-height', $.global.panel_height + 'px');
                    $(this).html('<i class="fa fa-arrows-alt"></i>');
                }
            });
        });
    </script>
@endpush
