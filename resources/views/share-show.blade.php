@extends("layouts.single")
@section('title', $pageItem->title)
@section('page-content')
<nav class="wz-page-control clearfix">
    <h1 class="wz-page-title">
        {{ $pageItem->title }}
    </h1>
    <hr />
</nav>
<div class="markdown-body" id="markdown-body">
    @if($type == 'markdown')
        <textarea id="append-test" style="display:none;">{{ $pageItem->content }}</textarea>
    @endif
</div>

<div class="text-center wz-panel-limit mt-3">~ END ~</div>
@endsection

@includeIf("components.{$type}-show")
