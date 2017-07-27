<!DOCTYPE html>
<html lang="zh-CN">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- 上述3个meta标签*必须*放在最前面，任何其他内容都*必须*跟随其后！ -->
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="favicon.ico">

    <title>Wizard API</title>

    <link href="/static/css/normalize.css" rel="stylesheet">
    <!-- Bootstrap core CSS -->
    <link href="https://cdn.bootcss.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet">

    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <link href="http://v3.bootcss.com/assets/css/ie10-viewport-bug-workaround.css" rel="stylesheet">

    <link rel="stylesheet" href="/static/vendor/editor-md/css/editormd.min.css" />

    <!-- Custom styles for this template -->
    <link href="/static/css/style.css" rel="stylesheet">

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://cdn.bootcss.com/html5shiv/3.7.3/html5shiv.min.js"></script>
      <script src="https://cdn.bootcss.com/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>

<body>

    <div class="container-fluid">
        <div class="header clearfix">
            <nav>
                <ul class="nav nav-pills pull-right">
                    <li role="presentation" class="active"><a href="#">Home</a></li>
                    <li role="presentation"><a href="#">About</a></li>
                    <li role="presentation"><a href="#">Contact</a></li>
                </ul>
            </nav>
            <h3 class="text-muted">Wizard API</h3>
        </div>

        <div class="row marketing">
            <div class="col-lg-12 wz-edit-control">
                <form class="form-inline">
                    <div class="form-group">
                        <input type="text" class="form-control" id="editor-title" placeholder="标题">
                    </div>
                    
                    <div class="form-group pull-right">
                        <div class="btn-group">
                            <button type="button" class="btn btn-success">保存</button>
                            <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="caret"></span>
                            </button>
                            <ul class="dropdown-menu">
                                <li><a href="#">另存为模板</a></li>
                                <li><a href="#">加入草稿箱</a></li>
                            </ul>
                        </div>
                    </div>
                </form>
            </div>
            <div class="col-lg-12">
                <div id="editormd">
                    <textarea style="display:none;">### Hello Editor.md !</textarea>
                </div>
            </div>
        </div>

        <footer class="footer">
            <p>&copy; 2017 Yunsom, Inc.</p>
        </footer>

    </div>
    <!-- /container -->

    <script src="https://cdn.bootcss.com/jquery/1.12.4/jquery.min.js"></script>
    <script src="https://cdn.bootcss.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa"
        crossorigin="anonymous"></script>

    <script src="/static/vendor/editor-md/editormd.min.js"></script>
    <script type="text/javascript">
        $(function () {
            var editor = editormd("editormd", {
                path: "/static/vendor/editor-md/lib/",
                height: 640,
            });
        });
    </script>

    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <script src="http://v3.bootcss.com/assets/js/ie10-viewport-bug-workaround.js"></script>
</body>

</html>