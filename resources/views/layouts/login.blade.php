<!doctype html>
<html lang="en">
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
    <link rel="stylesheet" href="{{ cdn_resource('/assets/vendor/bootstrap-material-design/css/bootstrap-material-design.min.css') }}">
    <style type="text/css">
        html,
        body {
            height: 100%;
        }

        body {
            display: -ms-flexbox;
            display: -webkit-box;
            display: flex;
            -ms-flex-align: center;
            -ms-flex-pack: center;
            -webkit-box-align: center;
            align-items: center;
            -webkit-box-pack: center;
            justify-content: center;
            padding-top: 40px;
            padding-bottom: 40px;
        }

        .form-signin {
            width: 100%;
            max-width: 330px;
            padding: 15px;
            margin: 0 auto;
        }

        .form-signin .checkbox {
            font-weight: 400;
        }
        .form-signin .form-control {
            position: relative;
            box-sizing: border-box;
            height: auto;
            padding: 10px;
            font-size: 16px;
        }
        .form-signin .form-control:focus {
            z-index: 2;
        }
        .form-signin input[type="email"] {
            margin-bottom: -1px;
            border-bottom-right-radius: 0;
            border-bottom-left-radius: 0;
        }

        .form-signin input[type="password"] {
            margin-bottom: 10px;
            border-top-left-radius: 0;
            border-top-right-radius: 0;
        }

        .main-card {
            min-width: 500px;
            background: #ffffff;
        }

        @media (min-width: 768px) {
            body {
                background: url("{{ config('wizard.login_background_img') }}") no-repeat;
                background-size: 100% auto;
            }
        }

        @media (max-width: 767px) {
            .main-card {
                background: none;
                box-shadow: none;
            }
        }
    </style>
</head>

<body class="text-center">

    <div class="card main-card">
        <div class="card-body">
            @yield('content')
        </div>
    </div>

<script src="{{ cdn_resource('/assets/vendor/jquery.min.js') }}"></script>
{{--<script src="/assets/vendor/bootstrap/js/bootstrap.min.js"></script>--}}
<script src="{{ cdn_resource('/assets/vendor/popper.js') }}"></script>
<script src="{{ cdn_resource('/assets/vendor/bootstrap-material-design/js/bootstrap-material-design.min.js') }}"></script>

@stack('script')

<script>
    $(function () {
        $('body').bootstrapMaterialDesign();
    });
</script>

</body>
</html>
