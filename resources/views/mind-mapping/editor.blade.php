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
    <title>思维导图</title>
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
        .control-panel {
            padding: 10px;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            border-bottom: 1px solid #ccc;
            background: #ffffff;
            z-index: 999;
        }
        .wz-toolbar {
            float: right;
            margin-right: 20px;
        }
    </style>
</head>
<body>
    <div class="row wz-full-box" id="wz-main-box">
        <div class="wz-mind-mapping-panel">
            <div class="control-panel">
                <div class="wz-toolbar">
                    <button class="wz-cancel">取消</button>
                    <button class="wz-save">保存</button>
                </div>
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
                @if (isset($readonly) && $readonly)
                draggable: false,
                contextMenu: false,
                toolBar: false,
                nodeMenu: false,
                keypress: false,
                @endif
            };

            let mind = new MindElixir(config);
            mind.init();

            // 高度调整，避免出现滚动条
            let mainPanel = $('.wz-mind-mapping-panel');

            @if (isset($readonly) && $readonly)
            mind.disableEdit();
            mainPanel.find('.outer').css('margin-top', 0);
            mainPanel.find('.control-panel').hide();
            @else
            mainPanel.height(mainPanel.height() - 42);
            mainPanel.find('.outer').css('margin-top', 42);
            @endif

            $('.wz-save').on('click', function(e) {
                e.preventDefault();

                const req = {
                    name: $(E('root')).text(),
                    description: '',
                    content: mind.getAllDataString(),
                };

                $.wz.request('POST', '{{ wzRoute('mind-mapping:save', ['ref_id' => $widget->ref_id ?? null]) }}', req, function(data) {
                    window.location.href = data.url;
                });
            });

        });
    </script>
</body>
</html>
