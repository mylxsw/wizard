@extends("layouts.single")
@section('title', $pageItem->title)
@section('page-content')
<nav class="wz-page-control clearfix">
    <h1 class="wz-page-title">
        {{ $pageItem->title }}
    </h1>
    @if(Auth::user())
    <a href="{{ wzRoute('project:home', ['id' => $project->id, 'p' => $pageItem->id]) }}"
       title="返回"
       class="btn btn-link float-right print-hide"><i class="material-icons">clear</i></a>
    <hr />
    @endif
</nav>
<div class="markdown-body" id="markdown-body">
    @if($type == 'markdown')
        <textarea id="append-test" style="display:none;">{{ str_replace('[SUB]', '', processMarkdown($pageItem->content)) }}</textarea>
    @endif
    @if($type == 'table')
        <textarea id="x-spreadsheet-content" class="d-none">{{ processSpreedSheet($pageItem->content) }}</textarea>
        <div id="x-spreadsheet"></div>
    @endif
</div>

<div class="text-center wz-panel-limit mt-3">~ END ~</div>
@endsection

@includeIf("components.{$type}-show")

@push('script-pre')
    <script>
        @if($type == 'table')
        $(function () {
            $('.wz-body').css('max-width', '100%');
            $('.wz-panel-limit').css('max-width', '100%');
        });
        @endif
    </script>
@endpush
