@push('stylesheet')
<link href="/assets/vendor/swagger-ui/swagger-ui.css" rel="stylesheet">
@endpush

@push('script')
<script src="/assets/vendor/swagger-ui/swagger-ui-bundle.js"></script>
<script src="/assets/vendor/swagger-ui/swagger-ui-standalone-preset.js"></script>
<script>
    $(function () {
        window.ui = SwaggerUIBundle({
            dom_id: '#markdown-body',
            presets: [
                SwaggerUIBundle.presets.apis,
                SwaggerUIStandalonePreset
            ],
            plugins: [
                SwaggerUIBundle.plugins.DownloadUrl
            ],
            validatorUrl: "",
            layout: "StandaloneLayout",
            @if(isset($isHistoryPage) && $isHistoryPage)
            url: "{!! wzRoute('project:doc:history:json', ['id' => $project->id, 'page_id' => $pageItem->id, 'history_id' => $history->id, 'only_body' => 1, 'ts' => microtime(true)]) !!}"
            @else
            url: "{!! wzRoute('project:doc:json', ['id' => $project->id, 'page_id' => $pageItem->id, 'only_body' => 1, 'ts' => microtime(true)])  !!}"
            @endif
        });
    });
</script>
@endpush