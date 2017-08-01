@extends('layouts.default')

@section('container-style', 'container-fluid')
@section('content')
    @include('layouts.navbar')

    <div class="row marketing">
        @include('components.error', ['error' => $errors ?? null])
        <form class="form-inline" method="POST"
              action="{{ $newPage ? wzRoute('project:doc:new:show', ['id' => $project->id]) : wzRoute('project:doc:edit:show', ['id' => $project->id, 'page_id' => $pageItem->id]) }}">

            @include('components.doc-edit', ['project' => $project, 'pageItem' => $pageItem ?? null, 'navigator' => $navigator])
            <input type="hidden" name="type" value="doc" />
            <div class="col-lg-12">
                <div id="editormd">
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
<script type="text/javascript">
    $(function () {
        var editor = editormd("editormd", {
            path: "/assets/vendor/editor-md/lib/",
            height: 640,
            taskList: true,
            tex: true,
            flowChart: true,
            sequenceDiagram: true,
            toolbarIcons: function () {
                return ["undo", "redo", "|",
                    "bold", "del", "italic", "quote", "|",
                    "h1", "h2", "h3", "h4", "h5", "h6", "|",
                    "list-ul", "list-ol", "hr", "|",
                    "link", "reference-link", "image", "code", "preformatted-text", "code-block", "table", "pagebreak", "|",
                    "goto-line", "watch", "preview", "fullscreen", "clear", "search", "|",
                    "template", "|",
                    "help", "info"
                ];
            },
            toolbarIconsClass: {
                template: "fa-flask"
            },
            toolbarHandlers: {
                template: function (cm, icon, cursor, selection) {
                    dialog = this.createDialog({
                        title: "选择模板",
                        width: 380,
                        height: 300,
                        content: $('#editor-template-dialog').html(),
                        mask: true,
                        drag: true,
                        lockScreen: true,
                        buttons: {
                            enter: ["确定", function () {
                                var template = this.find("input[name=template]:checked");
                                var content = Base64.decode(template.data('content'));

                                cm.replaceSelection(content);

                                this.hide().lockScreen(false).hideMask();

                                return false;
                            }],

                            cancel: ["取消", function () {
                                this.hide().lockScreen(false).hideMask();

                                return false;
                            }]
                        }
                    });
                }
            },
            lang: {
                toolbar: {
                    template: "选择模板"
                }
            }
        });
    });
</script>
<script type="text/html" id="editor-template-dialog">
    <div class="wz-template-dialog">
        @foreach(wzTemplates() as $temp)
            <div class="radio">
                <label>
                    <input type="radio" name="template" value="{{ $temp['id'] }}"
                           data-content="{{ base64_encode($temp['content']) }}" {{ $temp['default'] ? 'checked' : '' }}>
                    {{ $temp['name'] }}
                </label>
            </div>
        @endforeach
    </div>
</script>
@endpush