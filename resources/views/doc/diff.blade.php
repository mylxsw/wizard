@extends('layouts.default')

@section('container-style', 'container-fluid')
@section('content')
    @if(!$noheader)
        <nav class="wz-page-control clearfix">
            <h1 class="wz-page-title">
                @lang('document.document_differ')
            </h1>
            <ul class="nav nav-pills pull-right">
                <li><a href="javascript: window.history.go(-1)"
                       class="btn btn-link" style="margin-right: 30px;">@lang('common.btn_back')</a></li>
            </ul>
        </nav>
    @endif

    <div class="row wz-full-box" id="wz-main-box">
        <div id="wz-compared" class="wz-compare-container w-100">
            <div style="display: none" id="wz-diff-original">{!! base64_encode($differContents) !!}</div>
            @if($doc1title !== $doc2title)
            <div class="wz-title-changed">
                原标题 <s class="text-danger">{{ $doc2title }}</s> 修改为 <b class="text-success">{{ $doc1title }}</b>
            </div>
            @endif
            @if($doc1pid !== $doc2pid)
            <div class="wz-title-changed">
                上级目录ID <s class="text-danger">{{ $doc2pid }}</s> 修改为 <b class="text-success">{{ $doc1pid }}</b>
            </div>
            @endif
            <div id="wz-diff-result"></div>
        </div>
    </div>
@endsection

@push('stylesheet')
    <link rel="stylesheet" href="/assets/vendor/highlight/styles/github.css">
    <link href="/assets/vendor/diff2html/diff2html.min.css?{{ resourceVersion() }}" rel="stylesheet"/>
    <style type="text/css">
        .d2h-code-side-linenumber {
            padding-right: 10px;
        }
        .wz-title-changed {
            margin-bottom: 10px;
        }
    </style>
@endpush

@push('script')
    <script src="/assets/vendor/highlight/highlight.pack.js"></script>
    <script src="/assets/vendor/diff2html/diff2html.min.js"></script>
    <script src="/assets/vendor/diff2html/diff2html-ui.min.js"></script>
    <script src="/assets/vendor/base64.min.js"></script>
    <script>
        $(function () {
            var diff2htmlUi = new Diff2HtmlUI({diff: Base64.decode($('#wz-diff-original').text())});
            diff2htmlUi.draw('#wz-diff-result', {inputFormat: 'diff', showFiles: false, matching: 'lines', outputFormat: 'side-by-side'});
            diff2htmlUi.highlightCode('#wz-diff-result');
        });
    </script>
@endpush