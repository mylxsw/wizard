@extends('layouts.default')
@section('title', $project->name)
@section('container-style', 'container-fluid')
@section('content')
    @include('layouts.navbar')

    <div class="row marketing">
        @include('components.error', ['error' => $errors ?? null])
        <form class="form-inline" method="POST" id="wz-doc-edit-form"
              action="{{ $newPage ? wzRoute('project:doc:new:show', ['id' => $project->id]) : wzRoute('project:doc:edit:show', ['id' => $project->id, 'page_id' => $pageItem->id]) }}">

            @include('components.doc-edit', ['project' => $project, 'pageItem' => $pageItem ?? null, 'navigator' => $navigator])
            <input type="hidden" name="type" value="doc" />
            <div class="col-lg-12">
                <div id="editormd" class="wz-markdown-style-fix">
                    <textarea style="display:none;" name="content">{{ $pageItem->content or '' }}</textarea>
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
<script src="/assets/vendor/editor-md/editormd.min.js"></script>
<script src="/assets/js/markdown-editor.js?{{ resourceVersion() }}"></script>
<script type="text/javascript">
    $(function () {
        var editor = $.wz.mdEditor('editormd', {
            template: function () {
                return $('#editor-template-dialog').html();
            },
            templateSelected: function (dialog) {
                var template = dialog.find("input[name=template]:checked");

                return Base64.decode(template.data('content'));
            },
            lang: {
                chooseTemplate: '@lang('document.select_template')',
                confirmBtn: '@lang('common.btn_confirm')',
                cancelBtn: '@lang('common.btn_cancel')'
            }
        });

        $.global.getEditorContent = function () {
            return editor.getMarkdown();
        };
    });
</script>
<script type="text/html" id="editor-template-dialog">
    <div class="wz-template-dialog">
        @foreach(wzTemplates() as $temp)
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
</script>
@endpush