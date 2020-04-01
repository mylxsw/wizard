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
    <link rel="icon" type="image/png" href="{{ cdn_resource('/favorite.png') }}">

    <title>@yield('title') - {{ config('app.name', 'Wizard API') }}</title>

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

    @stack('stylesheet')

    <link href="/assets/css/style-dark.css?{{ resourceVersion() }}" rel="stylesheet">
</head>

<body>
@unless($hideGlobalAlert ?? false)
    @if(Auth::user() && !Auth::user()->isActivated())
        <div class="alert alert-danger" style="border-radius: 0; margin-bottom: 0;">
            <form action="{{ wzRoute('user:activate:send') }}" method="post"
                  id="form-send-activate-email">{{ csrf_field() }}</form>
            您尚未激活帐号，请先激活帐号后再进行操作<a href="#" data-form="#form-send-activate-email" wz-form-submit>【重新发送激活邮件】</a> 。
        </div>
    @endif
@endunless

@if(!isset($noheader) || !$noheader)
    @include('layouts.navbar')
@endif
<div class="wz-body @yield('container-style')">
    @yield('content')
</div>

@if(!isset($noheader) || !$noheader)
    <footer class="footer">
        <div class="@yield('container-style')">
            <p>
                &copy; {{ date('Y') }} {{ config('wizard.copyright', 'AICODE.CC') }}
                <a class="fa fa-github wz-version" target="_blank" href="https://github.com/mylxsw/wizard">
                    v{{ config('wizard.version', '1') }}</a>
                {!! statistics() !!}
            </p>
        </div>
    </footer>
@endif

<script src="{{ cdn_resource('/assets/vendor/jquery.min.js') }}"></script>
<script src="{{ cdn_resource('/assets/vendor/store.everything.min.js') }}"></script>
<script>
    $(function () {
        var currentTheme = store.get('wizard-theme');
        if (currentTheme === undefined) {
            currentTheme = '{{ config('wizard.theme') }}';
        }

        if (currentTheme === 'dark') {
            $('body').addClass('wz-dark-theme');
        }
    });
</script>

@stack('bottom')

<script src="{{ cdn_resource('/assets/vendor/popper.js') }}"></script>
<script src="{{ cdn_resource('/assets/vendor/bootstrap-material-design/js/bootstrap-material-design.min.js') }}"></script>
<script src="{{ cdn_resource('/assets/vendor/jquery.easing.js') }}"></script>
<script src="{{ cdn_resource('/assets/vendor/jquery.scrollUp.min.js') }}"></script>

<!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
<script src="{{ cdn_resource('/assets/vendor/ie10-viewport-bug-workaround.js') }}"></script>
<script src="{{ cdn_resource('/assets/vendor/layer/layer.js') }}"></script>
<script src="{{ cdn_resource('/assets/vendor/axios.min.js') }}"></script>
<script src="/assets/js/wizard.js?{{ resourceVersion() }}"></script>
<script src="/assets/js/app.js?{{ resourceVersion() }}"></script>
<script src="{{ cdn_resource('/assets/js/tagmanager.js') }}"></script>

@stack('script-pre')

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

        // 功能开发中提示消息
        $('[wz-wait-develop]').on('click', function () {
            $.wz.alert('@lang('common.not_ready_for_use')');
            return false;
        });

        // 滚动到顶部事件
        $.scrollUp({
            scrollText: '<span class="fa fa-chevron-up"></span>'
        });

        // 可关闭对话框关闭事件
        $('.alert-dismissible[data-alert-id]').on('closed.bs.alert', function () {
            store.set("alert-" + $(this).data('alert-id') + "-closed", (new Date()).getTime());
        }).each(function () {
            var lastClosedTime = store.get("alert-" + $(this).data('alert-id') + "-closed");
            if (lastClosedTime) {
                $(this).hide();
            }
        });

        // 所有js执行完后再执行
        window.setTimeout(function () {
            // 重置窗口大小，避免内容过少无法撑开页面
            var resize_window = function () {
                var window_height = $(window).height() - $('.wz-top-navbar').height() - $('.footer').height() - 82;
                var frame_height = $('.wz-main-container').height();
                if (frame_height === null) {
                    frame_height = $('.wz-main-container-full').height();
                }

                var minHeight = (window_height > frame_height ? window_height : frame_height) + "px";
                $($('.wz-panel-right').length ? '.wz-panel-right' : '.wz-body').css('min-height', minHeight);

                $.global.windowResize();
                $.global.panel_height = minHeight;
            };

            resize_window();
            $(window).on('resize', function () {
                resize_window();
            });

            // 鼠标经过提示
            $('[data-toggle="tooltip"]').tooltip({
                delay: {"show": 500, "hide": 100}
            });
        }, 500);

        // 左侧导航栏自适应布局切换
        $('.wz-left-main-full .wz-left-main-switch').on('click', function (e) {
            e.preventDefault();

            $('.wz-left-main').slideToggle();
            var icon = $(this).find('.fa');
            if (icon.hasClass('fa-angle-double-up')) {
                icon.removeClass('fa-angle-double-up').addClass('fa-angle-double-down');
            } else {
                icon.removeClass('fa-angle-double-down').addClass('fa-angle-double-up');
            }
        });
    });
</script>

@stack('script')


@section('bootstrap-material-init')
    <script>
        $(function () {
            $('body').bootstrapMaterialDesign();
        });
    </script>
@show

@if(config('wizard.version-check'))
    <script>
        $(function () {
            var version = '{{ config('wizard.version', '1') }}';
            $.getJSON('https://aicode.cc/wizard-update/version?callback=?', function (data) {
                if (data === undefined || data.tag_name === version) {
                    return;
                }

                $('.wz-version').append('<a href="' + data.html_url + '" target="_blank"><sup style="padding-left: 5px; color: red; font-weight: bold;" title="有新版本，请及时更新！' + data.body + '" >✱</sup></a>');
            });
        });
    </script>
@endif

</body>
</html>
