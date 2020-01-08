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
        <div class="wz-diff-control">
            <button class="btn btn-primary wz-switch-display">
                <i class="fa fa-columns" data-toggle="tooltip" title="切换展示方式"></i>
            </button>

            <div class="wz-title-changed">
                <i class="fa fa-quote-left"> 标题</i>
                @if($doc1title !== $doc2title)
                <s class="text-danger">{{ $doc2title }}</s> 修改为 <b class="text-success">{{ $doc1title }}</b>
                @else
                    <i class="text-success">无变更</i>
                @endif
            </div>


            <div class="wz-title-changed">
                <i class="fa fa-quote-left"> 上级目录ID</i>
                @if($doc1pid !== $doc2pid)
                <s class="text-danger">{{ $doc2pid }}</s> 修改为 <b class="text-success">{{ $doc1pid }}</b>
                @else
                    <i class="text-success">无变更</i>
                @endif
            </div>

        </div>
        <div id="wz-compared" class="wz-compare-container w-100">
            <div style="display: none" id="wz-diff-original">{!! base64_encode($differContents) !!}</div>

            <div id="wz-diff-result" class="wz-diff-result"></div>
        </div>
    </div>
@endsection

@push('stylesheet')
    <link rel="stylesheet" href="{{ cdn_resource('/assets/vendor/highlight/styles/github.css') }}">
    <link href="{{ cdn_resource('/assets/vendor/diff2html/diff2html.min.css') }}" rel="stylesheet"/>
    <style type="text/css">
        .d2h-code-side-linenumber {
            padding-right: 10px;
        }
        .d2h-file-wrapper {
            border: none;
            margin-bottom: 0;
        }
        .wz-title-changed {
            margin: 10px;
        }
        .wz-diff-control {
            padding: 10px;
            border: 1px dashed #ccc;
            border-radius: 3px;
            width: 100%;
            margin-bottom: 10px;
            position: relative;
            min-height: 100px;
        }

        .wz-switch-display {
            position: absolute;
            top: 10px;
            right: 10px;
        }
        .wz-compare-container {
            border: 1px dashed #ccc;
            border-radius: 3px;
        }
    </style>
@endpush

@push('script')
    <script src="{{ cdn_resource('/assets/vendor/highlight/highlight.pack.js') }}"></script>
    <script src="{{ cdn_resource('/assets/vendor/diff2html/diff2html.min.js') }}"></script>
    <script src="{{ cdn_resource('/assets/vendor/diff2html/diff2html-ui.min.js') }}"></script>
    <script src="{{ cdn_resource('/assets/vendor/base64.min.js') }}"></script>
    <script>
        $(function () {
            var switchDisplay = function(mode) {
                $('#wz-diff-result').html('');
                var diff2htmlUi = new Diff2HtmlUI({diff: Base64.decode($('#wz-diff-original').text())});
                diff2htmlUi.draw('#wz-diff-result', {inputFormat: 'diff', showFiles: false, matching: 'lines', outputFormat: mode});
                diff2htmlUi.highlightCode('#wz-diff-result');
            };

            var currentDisplayMode = 'line-by-line';
            switchDisplay(currentDisplayMode);

            $('.wz-switch-display').on('click', function () {
                if (currentDisplayMode === 'line-by-line') {
                    currentDisplayMode = 'side-by-side';
                    $(this).find('i').removeClass('fa-columns').addClass('fa-file');
                    switchDisplay(currentDisplayMode);
                } else {
                    $(this).find('i').removeClass('fa-file').addClass('fa-columns');
                    currentDisplayMode = 'line-by-line';
                    switchDisplay(currentDisplayMode);
                }
            });
        });
    </script>
@endpush