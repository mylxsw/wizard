{{ csrf_field() }}
<input type="hidden" name="project_id" id="editor-project_id" value="{{ $project->id or '' }}"/>
<input type="hidden" name="page_id" id="editor-page_id" value="{{ $pageItem->id or '' }}">
<input type="hidden" name="pid" id="editor-pid" value="{{ $pageItem->pid or '' }}">
<div class="col-lg-12 wz-edit-control">

    <div class="form-group input-group">
        <span class="input-group-addon" title="项目名称">{{ $project->name }}</span>
        <input type="text" class="form-control wz-input-long" name="title" id="editor-title"
               value="{{ $pageItem->title or '' }}" placeholder="标题">
    </div>

    <div class="form-group">
        <select class="form-control" name="pid">
            <option value="0">无上级页面</option>
            @include('components.doc-options', ['navbars' => $navigator, 'level' => 0])
        </select>
    </div>

    <div class="form-group pull-right">
        <div class="btn-group">
            <button type="submit" class="btn btn-success">保存</button>
            <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown"
                    aria-haspopup="true" aria-expanded="false">
                <span class="caret"></span>
            </button>
            <ul class="dropdown-menu">
                <li><a href="#">另存为模板</a></li>
                <li><a href="#">加入草稿箱</a></li>
            </ul>
        </div>
        <a href="{{ wzRoute('project:home', ['id' => $project->id] + (empty($pageItem) ? [] : ['p' => $pageItem->id])) }}" class="btn btn-default">返回</a>
    </div>
</div>