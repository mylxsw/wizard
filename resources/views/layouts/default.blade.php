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
    <link rel="icon" href="../../../public/favicon.ico">

    <title>{{ config('app.name', 'Wizard API') }}</title>

    <link href="/assets/css/normalize.css" rel="stylesheet">
    <!-- Bootstrap core CSS -->
    <link href="//cdn.bootcss.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet">

    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <link href="//v3.bootcss.com/assets/css/ie10-viewport-bug-workaround.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="/assets/css/style.css" rel="stylesheet">

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="//cdn.bootcss.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="//cdn.bootcss.com/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

    @stack('stylesheet')
</head>

<body>
<div class="@yield('container-style')">
    @yield('content')
    <footer class="footer">
        <p>&copy; {{ date('Y') }} AICODE.CC</p>
    </footer>
</div>
<script src="//cdn.bootcss.com/jquery/1.12.4/jquery.min.js"></script>
<script src="//cdn.bootcss.com/bootstrap/3.3.7/js/bootstrap.min.js"
        integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa"
        crossorigin="anonymous"></script>

<!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
<script src="//v3.bootcss.com/assets/js/ie10-viewport-bug-workaround.js"></script>
<script src="/assets/vendor/layer/layer.js"></script>
<script src="/assets/vendor/axios.min.js"></script>
<script src="/assets/js/wizard.js"></script>
<script src="/assets/js/app.js"></script>

<script>
    $(function () {
        {{-- 页面提示消息（上一个页面操作的结果） --}}
        @if (session('alert.message'))
            layer.msg("{{ session('alert.message') }}");
        @endif
    });
</script>

@stack('script')
</body>
</html>
