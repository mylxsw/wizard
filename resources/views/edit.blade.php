@extends('layout.default')

@section('container-style', 'container-fluid')
@section('content')
    @include('layout.navbar')

    <div class="row marketing">
        <form class="form-inline" method="POST" action="/{{ .project.ID }}/page/{{ .page.ID }}">
            <input type="hidden" name="project_id" id="editor-project_id" value="{{ .project.ID }}" />
            <input type="hidden" name="_method" id="editor-method" value="{{ if .params.new }}post{{ else }}put{{ end }}" />
            <div class="col-lg-12 wz-edit-control">
                <div class="form-group">
                    <input type="text" class="form-control" name="title" id="editor-title"
                           value="{{ .page.Title }}" placeholder="标题" >
                </div>

                <div class="form-group pull-right">
                    <div class="btn-group">
                        <button type="submit" class="btn btn-success">保存</button>
                        <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <span class="caret"></span>
                        </button>
                        <ul class="dropdown-menu">
                            <li><a href="#">另存为模板</a></li>
                            <li><a href="#">加入草稿箱</a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-lg-12">
                <div id="editormd">
                    <textarea style="display:none;" name="content">{{ .page.Content }}</textarea>
                </div>
            </div>
        </form>
    </div>
@endsection

@push('stylesheet')
<link href="/assets/vendor/editor-md/css/editormd.min.css" rel="stylesheet"/>
@endpush

@push('script')
<script src="/assets/vendor/editor-md/editormd.min.js"></script>
<script type="text/javascript">
    $(function () {
        var editor = editormd("editormd", {
            path: "/assets/vendor/editor-md/lib/",
            height: 640
        });
    });
</script>
@endpush