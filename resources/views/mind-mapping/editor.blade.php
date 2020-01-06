<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- 上述3个meta标签*必须*放在最前面，任何其他内容都*必须*跟随其后！ -->
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- 网站icon，来自于Google开源图标 -->
    <link rel="icon" type="image/png" href="/favorite.png">
    <title>{{ $widget->name ?? '创建思维导图' }}</title>
    <style type="text/css">
        .outer {
            position: relative;
            margin-top: 42px;
        }
        #map {
            height: 100%;
            width: 100%;
            overflow: auto;
        }
        .wz-mind-mapping-panel {
            height: 100%;
            position: relative;
        }
        .wz-control-panel {
            padding: 10px;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            background: #f9f9f9;
            z-index: 999;
            box-shadow: 0px 2px 2px #ccc;
        }
        .wz-toolbar {
            float: right;
            padding: 0!important;
        }

        .wz-toolbar-item {
            padding: 10px 20px;
            background: #0d5aa7;
            color: #ffffff;
            margin-right: 10px;
            cursor: pointer;
        }
        .wz-toolbar-item.wz-edit-toggle-mode,
        .wz-toolbar-item.wz-edit-cancel-mode {
            background: #e8e8e8;
            color: #666666;
        }
        .wz-toolbar-item:hover {
            background: #212529;
            color: #ffffff;
        }
    </style>
</head>
<body>
    <div class="row wz-full-box" id="wz-main-box">
        <div class="wz-mind-mapping-panel">
            <div class="wz-control-panel">
                <div class="wz-toolbar">
                    @if ($readonly)
                    <div class="wz-read-mode">
                        <a class="wz-edit-toggle-mode wz-toolbar-item">编辑</a>
                    </div>
                    @else
                    <div class="wz-edit-mode">
                        @if(!empty($widget))
                        <a class="wz-toolbar-item wz-edit-cancel-mode">取消</a>
                        @endif
                        <a class="wz-save wz-toolbar-item">保存</a>
                    </div>
                    @endif
                </div>
                <div style="clear: both"></div>
            </div>
            <div class="outer">
                <div id="map"></div>
            </div>
        </div>
    </div>

    <script src="/assets/vendor/jquery.min.js"></script>
    <script src="/assets/vendor/layer/layer.js"></script>
    <script src="/assets/vendor/axios.min.js"></script>
    <script src="/assets/vendor/base64.min.js"></script>
    <script src="/assets/vendor/mind-elixir.js"></script>
    <script src="/assets/js/wizard.js"></script>
    <script src="/assets/js/app.js"></script>
    <script>

        $(function () {
            @if (!empty($widget))
                let data = JSON.parse(Base64.decode('{{ base64_encode($widget->content ?? '') }}'));
            @else
                let data = MindElixir.new('主题名称');
            @endif
            let config = {
                el: '#map',
                direction: MindElixir.RIGHT,
                data: data,
                toolBar: true,
                @if (isset($readonly) && $readonly)
                draggable: false,
                contextMenu: false,
                nodeMenu: false,
                keypress: false,
                @endif
            };

            let mind = new MindElixir(config);
            mind.init();

            window.saveMindMapping = function (callback) {
                const req = {
                    name: $(E('root')).text(),
                    description: '',
                    content: mind.getAllDataString(),
                };

                $.wz.request('POST', '{{ wzRoute('mind-mapping:save', ['ref_id' => $widget->ref_id ?? null]) }}', req, function(data) {
                    callback(data);
                });
            };

            // 高度调整，避免出现滚动条
            let mainPanel = $('.wz-mind-mapping-panel');

            mainPanel.height(mainPanel.height() - 42);
            mainPanel.find('.outer').css('margin-top', 42);

            @if ($readonly)
            mind.disableEdit();
            @endif

            @if(!empty($widget))
            $('.wz-edit-toggle-mode').on('click', function(e) {
                e.preventDefault();
                // let markdownEditor = window.parent.$.global.markdownEditor;
                // $.proxy(markdownEditor.toolbarHandlers.mindMapping, markdownEditor)();
                window.location.href = '{!! wzRoute('mind-mapping:editor', ['ref_id' => $widget->ref_id, 'readonly' => false]) !!}';
            });
            $('.wz-edit-cancel-mode').on('click', function(e) {
                e.preventDefault();
                window.location.href = '{!! wzRoute('mind-mapping:editor', ['ref_id' => $widget->ref_id, 'readonly' => true]) !!}'
            });
            @endif
            $('.wz-save').on('click', function(e) {
                e.preventDefault();

                window.saveMindMapping(function (data) {
                    window.location.href = data.url;
                });
            });
        });
    </script>
</body>
</html>
