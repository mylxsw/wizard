@push('stylesheet')
<link href="/assets/vendor/swagger-editor/swagger-editor.css" rel="stylesheet">
@endpush

@push('script')
<script src="/assets/vendor/swagger-editor/swagger-editor-bundle.js"></script>
<script src="/assets/vendor/swagger-editor/swagger-editor-standalone-preset.js"></script>

<script>
    $(function() {
        $.global.getEditorContent = function () {
            return $('#editor-content').val();
        };

        window.editor = SwaggerEditorBundle({
            dom_id: '#editor-content',
            layout: 'StandaloneLayout',
            presets: [
                SwaggerEditorStandalonePreset
            ],
            url: "{{ route('project:doc:json', ['id' => $project->id, 'page_id' => $pageItem->id, 'only_body' => 1]) }}"
        });
    });
</script>
@endpush