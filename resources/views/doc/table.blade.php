@extends('layouts.default')
@section('title', $project->name)
@section('container-style', 'container-fluid')
@section('content')

    <div class="row marketing wz-main-container-full">
        <form style="width: 100%;" method="POST" id="wz-doc-edit-form"
              action="{{ $newPage ? wzRoute('project:doc:new:show', ['id' => $project->id]) : wzRoute('project:doc:edit:show', ['id' => $project->id, 'page_id' => $pageItem->id]) }}">

            @include('components.doc-edit', ['project' => $project, 'pageItem' => $pageItem ?? null, 'navigator' => $navigator])
            <input type="hidden" name="type" value="table" />

            <div id="xspreadsheet-content" style="display: none;">{{ base64_encode(processSpreedSheet($pageItem->content ?? '')) }}</div>
            <div class="col-row" id="xspreadsheet"></div>
        </form>
    </div>
@endsection

@push('bottom')

@endpush

@push('stylesheet')
    <link rel="stylesheet" href="{{ cdn_resource('/assets/vendor/x-spreadsheet/xspreadsheet.css') }}">
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
    <script src="{{ cdn_resource('/assets/vendor/base64.min.js') }}"></script>
    <script src="{{ cdn_resource('/assets/vendor/x-spreadsheet/xspreadsheet.js') }}"></script>
{{--    <script src="{{ cdn_resource('/assets/vendor/x-spreadsheet/locale/zh-cn.js') }}"></script>--}}

    <script>
        $(function() {

            var savedContent = $('#xspreadsheet-content').html();
            if (savedContent === '') {
                savedContent = "[{\"name\":\"sheet1\",\"cols\":{\"len\":25},\"rows\":{\"len\":100}}]";
            } else {
                savedContent = Base64.decode(savedContent);
            }

            var options = {
                mode: 'edit',
                showToolbar: true,
                showGrid: true,
                showContextmenu: true,
                view: {
                    height: () => document.documentElement.clientHeight - 20,
                    width: () => document.documentElement.clientWidth - 20,
                },
                row: {
                    len: {{ config('wizard.spreedsheet.max_rows') }},
                    height: 25,
                },
                col: {
                    len: {{ config('wizard.spreedsheet.max_cols') }},
                    width: 100,
                    indexWidth: 60,
                    minWidth: 60,
                },
            };

            // x.spreadsheet.locale('zh-cn');

            var sheet = x.spreadsheet('#xspreadsheet', options);
            var data = JSON.parse(savedContent);
            for (var i in data) {
                data[i].cols.len = options.col.len;
                data[i].rows.len = options.row.len;
            }

            sheet.loadData(data);

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
                var data = JSON.parse(content);
                for (var i in data) {
                    data[i].cols.len = options.col.len;
                    data[i].rows.len = options.row.len;
                }

                sheet.loadData(data);
            };

        });
    </script>
@endpush
