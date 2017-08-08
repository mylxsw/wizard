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
</style>
@endpush

@push('script')
<script src="/assets/vendor/swagger-editor/swagger-editor-bundle.js"></script>
<script src="/assets/vendor/swagger-editor/swagger-editor-standalone-preset.js"></script>

<script>
    $(function() {
        $.global.getEditorContent = function () {
            return window.editor.specSelectors.specStr();
        };

        window.editor = SwaggerEditorBundle({
            dom_id: '#editor-content',
            layout: 'EditorLayout',
            presets: [
                SwaggerEditorStandalonePreset
            ],
            url: "{{ wzRoute('project:doc:json', ['id' => $project->id, 'page_id' => $pageItem->id, 'only_body' => 1, 'ts' => microtime(true)]) }}"
        });
    });
</script>
@endpush