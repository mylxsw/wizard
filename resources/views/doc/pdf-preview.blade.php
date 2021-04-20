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
    <title>{{ $widget->name ?? 'PDF 预览' }}</title>

    <link href="{{ cdn_resource('/assets/css/normalize.css') }}" rel="stylesheet">
    <link href="{{ cdn_resource('/assets/css/tagmanager.css') }}" rel="stylesheet">

    <!-- Bootstrap core CSS -->
    {{--<link href="{{ cdn_resource('/assets/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">--}}
    <link rel="stylesheet" href="{{ cdn_resource('/assets/vendor/material-design-icons/material-icons.css') }}">
    <link rel="stylesheet" href="{{ cdn_resource('/assets/vendor/bootstrap-material-design/css/bootstrap-material-design.min.css') }}">
    <link href="{{ cdn_resource('/assets/vendor/font-awesome4/css/font-awesome.min.css') }}" rel="stylesheet">

    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <link href="{{ cdn_resource('/assets/vendor/ie10-viewport-bug-workaround.css') }}" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="/assets/css/style.css?{{ resourceVersion() }}" rel="stylesheet">

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="{{ cdn_resource('/assets/vendor/html5shiv.min.js') }}"></script>
    <script src="{{ cdn_resource('/assets/vendor/respond.min.js') }}"></script>
    <![endif]-->
    <link href="/assets/css/style-dark.css?{{ resourceVersion() }}" rel="stylesheet">
    <style type="text/css">
        .wz-full-box {
            position: relative;
            margin: auto;
            display: block;
        }
        .pdf-control-panel {
            position: absolute;
            top: 10px;
            right: 10px;

            background: #ffffff;
            filter:alpha(Opacity=50);
            -moz-opacity:0.5;
            opacity: 0.5;

            padding: 5px;
            border-radius: 7px;
        }
        .pdf-control-panel:hover {
            filter:alpha(Opacity=100);
            -moz-opacity:1;
            opacity: 1;
        }
        .wz-full-box canvas {
            max-width: 100%;
            display: block;
            margin: auto;
        }
    </style>
</head>
<body>
<div class="row wz-full-box" id="wz-main-box">
    @if($mode === 'embed')
    <object data="{{ $pdf }}" type="application/pdf" width="100%" height="100%">
        <p>Alternative text - include a link <a href="{{ $pdf }}">to the PDF!</a></p>
    </object>
    @else
    <div class="pdf-control-panel">
        <button class="btn btn-primary" id="prev">上一页</button>
        <button class="btn btn-primary" id="next">下一页</button>
        &nbsp; &nbsp;
        <span>页码: <span id="page_num"></span> / <span id="page_count"></span></span>
    </div>
    <canvas id="the-canvas"></canvas>
    @endif
</div>
@if($mode !== 'embed')
<script src="/assets/vendor/jquery.min.js"></script>
<script src="{{ cdn_resource('/assets/vendor/popper.js') }}"></script>
<script src="{{ cdn_resource('/assets/vendor/bootstrap-material-design/js/bootstrap-material-design.min.js') }}"></script>
<script src="{{ cdn_resource('/assets/vendor/store.everything.min.js') }}"></script>
<script src="/assets/vendor/pdf/pdf.js"></script>
<script>
    $(function () {
        let body = $('body');
        let themeSwitch = function() {
            let currentTheme = store.get('wizard-theme');
            if (currentTheme === 'dark' && !body.hasClass('wz-dark-theme')) {
                $('body').addClass('wz-dark-theme');
            }

            if (currentTheme !== 'dark' && body.hasClass('wz-dark-theme')) {
                $('body').removeClass('wz-dark-theme');
            }
        };
        themeSwitch();
        window.setInterval(themeSwitch, 3000);
    });
</script>
<script>
$(function() {
    let url = "{{ $pdf }}";
    pdfjsLib.GlobalWorkerOptions.workerSrc = "/assets/vendor/pdf/pdf.worker.js";
    var pdfDoc = null,
        pageNum = 1,
        pageRendering = false,
        pageNumPending = null,
        scale = 1,
        canvas = document.getElementById('the-canvas'),
        ctx = canvas.getContext('2d');

    /**
     * Get page info from document, resize canvas accordingly, and render page.
     * @param num Page number.
     */
    function renderPage(num) {
        pageRendering = true;
        // Using promise to fetch the page
        pdfDoc.getPage(num).then(function(page) {
            var viewport = page.getViewport({scale: scale});
            canvas.height = viewport.height;
            canvas.width = viewport.width;

            // Render PDF page into canvas context
            var renderContext = {
                canvasContext: ctx,
                viewport: viewport
            };
            var renderTask = page.render(renderContext);

            // Wait for rendering to finish
            renderTask.promise.then(function() {
                pageRendering = false;
                if (pageNumPending !== null) {
                    // New page rendering is pending
                    renderPage(pageNumPending);
                    pageNumPending = null;
                }
            });
        });

        // Update page counters
        document.getElementById('page_num').textContent = num;
    }

    /**
     * If another page rendering in progress, waits until the rendering is
     * finised. Otherwise, executes rendering immediately.
     */
    function queueRenderPage(num) {
        if (pageRendering) {
            pageNumPending = num;
        } else {
            renderPage(num);
        }
    }

    /**
     * Displays previous page.
     */
    function onPrevPage() {
        if (pageNum <= 1) {
            return;
        }
        pageNum--;
        queueRenderPage(pageNum);
    }
    document.getElementById('prev').addEventListener('click', onPrevPage);

    /**
     * Displays next page.
     */
    function onNextPage() {
        if (pageNum >= pdfDoc.numPages) {
            return;
        }
        pageNum++;
        queueRenderPage(pageNum);
    }
    document.getElementById('next').addEventListener('click', onNextPage);

    /**
     * Asynchronously downloads PDF.
     */
    pdfjsLib.getDocument(url).promise.then(function(pdfDoc_) {
        pdfDoc = pdfDoc_;
        document.getElementById('page_count').textContent = pdfDoc.numPages;

        // Initial/first page rendering
        renderPage(pageNum);
    });

    $('body').bootstrapMaterialDesign();
});

</script>
@endif
</body>
</html>