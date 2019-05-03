@extends('layouts.default')
@section('title', $project->name)
@section('container-style', 'container-fluid')
@section('content')

    <div class="row marketing wz-main-container-full">
        <form style="width: 100%;" method="POST" id="wz-doc-edit-form"
              action="{{ $newPage ? wzRoute('project:doc:new:show', ['id' => $project->id]) : wzRoute('project:doc:edit:show', ['id' => $project->id, 'page_id' => $pageItem->id]) }}">

            @include('components.doc-edit', ['project' => $project, 'pageItem' => $pageItem ?? null, 'navigator' => $navigator])
            <input type="hidden" name="type" value="table" />

            <div id="xspreadsheet-content" style="display: none;">{{ $pageItem->content ?? '' }}</div>
            <div class="col-row" id="xspreadsheet"></div>
        </form>
    </div>
@endsection

@push('bottom')

@endpush

@push('stylesheet')
    <link rel="stylesheet" href="/assets/vendor/x-spreadsheet/xspreadsheet.css">
    <style>
        #xspreadsheet * {
            box-sizing: initial;
        }

        #xspreadsheet {
            border: 1px dashed #159e92;
        }
    </style>
@endpush

@push('script')
    <script src="/assets/vendor/base64.min.js"></script>
    <script src="/assets/vendor/x-spreadsheet/xspreadsheet.js"></script>
    {{--<script src="/assets/vendor/x-spreadsheet/locale/zh-cn.js"></script>--}}

    <script>
        $(function() {

            var savedContent = $('#xspreadsheet-content').html();
            if (savedContent === '') {
                savedContent = "{}";
            }

            var options = {
                showToolbar: true,
                showGrid: true,
                showContextmenu: true,
                view: {
                    height: () => document.documentElement.clientHeight - 20,
                    width: () => document.documentElement.clientWidth - 20,
                },
                row: {
                    len: 100,
                    height: 25,
                },
                col: {
                    len: 26,
                    width: 100,
                    indexWidth: 60,
                    minWidth: 60,
                },
            };

            // x.spreadsheet.locale('zh-cn');

            var sheet = x.spreadsheet('#xspreadsheet', options);
            sheet.loadData(JSON.parse(savedContent));

            // 获取编辑器中的内容
            $.global.getEditorContent = function () {
                // return window.editor.specSelectors.specStr();
                return JSON.stringify(sheet.getData());
            };

            // 获取swagger编辑器本次存储内容的key
            $.global.getDraftKey = function() {
                return 'x-spreadsheet-content-{{ $project->id ?? '' }}-{{ $pageItem->id ?? '' }}';
            };

            // 更新编辑器内容
            $.global.updateEditorContent = function (content) {
                sheet.loadData(JSON.parse(content));
            };

        });
    </script>
@endpush
