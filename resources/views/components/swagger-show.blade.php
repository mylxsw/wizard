@push('stylesheet')
<link href="{{ cdn_resource('/assets/vendor/swagger-ui/swagger-ui.css') }}" rel="stylesheet">
@endpush

@push('script')
<script src="{{ cdn_resource('/assets/vendor/swagger-ui/swagger-ui-bundle.js') }}"></script>
<script src="{{ cdn_resource('/assets/vendor/swagger-ui/swagger-ui-standalone-preset.js') }}"></script>
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
            layout: "BaseLayout",
            @if(isset($isHistoryPage) && $isHistoryPage)
            url: "{!! wzRoute('project:doc:history:json', ['code' => $code ?? '', 'id' => $project->id, 'page_id' => $pageItem->id, 'history_id' => $history->id, 'only_body' => 1, 'ts' => microtime(true)]) !!}"
            @else
            url: "{!! wzRoute('project:doc:json', ['code' => $code ?? '','id' => $project->id, 'page_id' => $pageItem->id, 'only_body' => 1, 'ts' => microtime(true)])  !!}"
            @endif
        });

        window.setTimeout(function () {
            $.wz.imageClick('#markdown-body');
            // $('.swagger-ui section.models h4').trigger('click');
        }, 3000);
    });
</script>
@endpush