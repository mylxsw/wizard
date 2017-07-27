{{ template "layout/header.tpl" }}

    <div class="container-fluid">
        {{ template "layout/navbar.tpl" }}

        <div class="row marketing">
            <div class="col-lg-12 wz-edit-control">
                <form class="form-inline">
                    <div class="form-group">
                        <input type="text" class="form-control" id="editor-title" 
                            value="{{ .title }}" placeholder="标题" >
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
                    <textarea style="display:none;">{{ .content }}</textarea>
                </div>
            </div>
        </div>

        {{ template "layout/copyright.tpl" }}

    </div>
    
    <link href="/static/vendor/editor-md/css/editormd.min.css" rel="stylesheet"/>
    <script src="/static/vendor/editor-md/editormd.min.js"></script>
    <script type="text/javascript">
        $(function () {
            var editor = editormd("editormd", {
                path: "/static/vendor/editor-md/lib/",
                height: 640,
            });
        });
    </script>

{{ template "layout/footer.tpl" }}