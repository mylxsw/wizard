@extends('layouts.default')
@section('title', $project->name)
@section('container-style', 'container-fluid')
@section('content')

    <div class="row marketing wz-main-container-full">
        @include('components.error', ['error' => $errors ?? null])
        <form class="w-100" method="POST" id="wz-doc-edit-form"
              action="{{ $newPage ? wzRoute('project:doc:new:show', ['id' => $project->id]) : wzRoute('project:doc:edit:show', ['id' => $project->id, 'page_id' => $pageItem->id]) }}">

            @include('components.doc-edit', ['project' => $project, 'pageItem' => $pageItem ?? null, 'navigator' => $navigator])
            <div class="row">
                <input type="hidden" name="type" value="doc"/>
                <div class="col" style="padding-left: 0; padding-right: 0;">
                    <div id="editormd" class="wz-markdown-style-fix">
                        <textarea style="display:none;" name="content">{{ $pageItem->content or '' }}</textarea>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection

@push('stylesheet')
    <link href="/assets/vendor/editor-md/css/editormd.min.css" rel="stylesheet"/>
@endpush

@push('script')
    <script src="/assets/vendor/base64.min.js"></script>
    <script src="/assets/vendor/editor-md/lib/marked.min.js"></script>
    <script src="/assets/vendor/editor-md/lib/prettify.min.js"></script>

    <script src="/assets/vendor/editor-md/lib/raphael.min.js"></script>
    <script src="/assets/vendor/editor-md/lib/underscore.min.js"></script>
    <script src="/assets/vendor/editor-md/lib/sequence-diagram.min.js"></script>
    <script src="/assets/vendor/editor-md/lib/flowchart.min.js"></script>
    <script src="/assets/vendor/editor-md/lib/jquery.flowchart.min.js"></script>
    <script src="/assets/vendor/editor-md/editormd.js"></script>
    <script src="/assets/js/markdown-editor.js?{{ resourceVersion() }}"></script>
    <script type="text/javascript">
        $(function () {
            var editor = $.wz.mdEditor('editormd', {
                template: function () {
                    return $('#editor-template-dialog').html();
                },
                templateSelected: function (dialog) {
                    var template = dialog.find("input[name=template]:checked");
                    if (template.data('content') === '') {
                        return '';
                    }

                    try {
                        return Base64.decode(template.data('content'))
                    } catch (ex) {
                        return '';
                    }
                },
                lang: {
                    chooseTemplate: '@lang('document.select_template')',
                    confirmBtn: '@lang('common.btn_confirm')',
                    cancelBtn: '@lang('common.btn_cancel')'
                }
            });

            $.global.getEditorContent = function () {
                try {
                    return editor.getMarkdown();
                } catch (e) {}

                return '';
            };

            $.global.getDraftKey = function () {
                return 'markdown-editor-content-{{ $project->id ?? '' }}-{{ $pageItem->id ?? '' }}';
            };

            $.global.updateEditorContent = function (content) {
                editor.setMarkdown(content)
            };
        });
    </script>
    <script type="text/html" id="editor-template-dialog">
        <form>
            <div class="wz-template-dialog">
                @foreach(wzTemplates() as $temp)
                    <div>
                        <label title="{{ $temp['description'] }}">
                            <input type="radio" name="template" value="{{ $temp['id'] }}"
                                   data-content="{{ base64_encode($temp['content']) }}" {{ $temp['default'] ? 'checked' : '' }}>
                            {{ $temp['name'] }}
                            @if($temp['scope'] == \App\Repositories\Template::SCOPE_PRIVATE)
                                【@lang('project.privilege_private')】
                            @endif
                        </label>
                    </div>
                @endforeach
            </div>
        </form>
    </script>
@endpush

@section('bootstrap-material-init')
    <!-- 没办法，material-design与editor-md的js冲突，导致editor-md无法自动滚动 -->
@endsection