<!DOCTYPE html>
<html lang="zh-CN">
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

    <title>@yield('title') - {{ config('app.name', 'Wizard API') }}</title>

    <link href="/assets/css/normalize.css" rel="stylesheet">
    <!-- Bootstrap core CSS -->
    <link href="/assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <link href="/assets/vendor/ie10-viewport-bug-workaround.css" rel="stylesheet">

    {{--<link href="/assets/vendor/animate.css" rel="stylesheet">--}}

    <!-- Custom styles for this template -->
    <link href="/assets/css/style.css?{{ resourceVersion() }}" rel="stylesheet">

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="/assets/vendor/html5shiv.min.js"></script>
    <script src="/assets/vendor/respond.min.js"></script>
    <![endif]-->

    @stack('stylesheet')
</head>

<body>
<div class="@yield('container-style')">
    @yield('content')
    @if(!isset($noheader) || !$noheader)
    <footer class="footer">
        <p>&copy; {{ date('Y') }} AICODE.CC</p>
    </footer>
    @endif
</div>

@stack('bottom')
<script src="/assets/vendor/jquery.min.js"></script>
<script src="/assets/vendor/bootstrap/js/bootstrap.min.js"></script>
<script src="/assets/vendor/jquery.easing.js"></script>
<script src="/assets/vendor/jquery.scrollUp.min.js"></script>

<!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
<script src="/assets/vendor/ie10-viewport-bug-workaround.js"></script>
<script src="/assets/vendor/layer/layer.js"></script>
<script src="/assets/vendor/axios.min.js"></script>
<script src="/assets/js/wizard.js?{{ resourceVersion() }}"></script>
<script src="/assets/js/app.js?{{ resourceVersion() }}"></script>

<script>
    $(function () {
        {{-- 页面提示消息（上一个页面操作的结果） --}}
        @if (session('alert.message.info'))
            $.wz.message("{{ session('alert.message') }}");
        @elseif (session('alert.message.success'))
            $.wz.message_success("{{ session('alert.message.success') }}");
        @elseif (session('alert.message.error'))
            $.wz.message_failed("{{ session('alert.message.error') }}");
        @endif

        $('[wz-wait-develop]').on('click', function () {
            $.wz.alert('@lang('common.not_ready_for_use')');
            return false;
        });

        $.scrollUp({
            scrollText: '<span class="glyphicon glyphicon-chevron-up"></span>'
        });
    });
</script>

@stack('script')
</body>
</html>
