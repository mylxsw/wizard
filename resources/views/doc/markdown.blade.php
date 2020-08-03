@extends('layouts.default')
@section('title', $project->name)
@section('container-style', 'container-fluid')
@section('content')

    <div class="row marketing wz-main-container-full">
        <form class="w-100" method="POST" id="wz-doc-edit-form"
              action="{{ $newPage ? wzRoute('project:doc:new:show', ['id' => $project->id]) : wzRoute('project:doc:edit:show', ['id' => $project->id, 'page_id' => $pageItem->id]) }}">

            @include('components.doc-edit', ['project' => $project, 'pageItem' => $pageItem ?? null, 'navigator' => $navigator])
            <div class="row">
                <input type="hidden" name="type" value="markdown"/>
                <div class="col" style="padding-left: 0; padding-right: 0;">
                    <div id="editormd" class="wz-markdown-style-fix">
                        <textarea style="display:none;" name="content">{{ $pageItem->content ?? '' }}</textarea>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection

@push('stylesheet')
    <link href="{{ cdn_resource('/assets/vendor/editor-md/css/editormd.min.css') }}" rel="stylesheet"/>
@endpush

@push('script')
    <script src="{{ cdn_resource('/assets/vendor/base64.min.js') }}"></script>
    <script src="{{ cdn_resource('/assets/vendor/editor-md/lib/raphael.min.js') }}"></script>
    <script src="{{ cdn_resource('/assets/vendor/editor-md/lib/underscore.min.js') }}"></script>
    <script src="{{ cdn_resource('/assets/vendor/editor-md/editormd.js') }}?{{ resourceVersion() }}"></script>
    <script src="{{ cdn_resource('/assets/js/markdown-editor.js') }}?{{ resourceVersion() }}"></script>
    <script type="text/javascript">
        $(function () {

            {{-- 从 2019-12-16T21:54:00+08:00 开始创建的新文档，使用最新的 marked 库，对 Markdown 文档格式要求更为严格一些，也更加规范，这里是对之前已经创建的不符合规范的文档做一个兼容 --}}
            @if(markdownCompatibilityStrict($pageItem ?? null))
                editormd.markedURL.js = '{{ cdn_resource('/assets/vendor/editor-md/lib/marked.min') }}';
            @else
                editormd.markedURL.js = '{{ cdn_resource('/assets/vendor/editor-md/lib/marked-0.3.3.min') }}';
            @endif

            editormd.defaults.resourcesVersion = "{{ resourceVersion() }}";

            // 初始化 Editor.md
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

            $.global.markdownEditor = editor;

            $.global.getEditorContent = function () {
                try {
                    return editor.getMarkdown();
                } catch (e) {
                }

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