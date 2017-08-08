@extends('layouts.default')

@section('container-style', 'container-fluid')
@section('content')
    @include('layouts.navbar')

    <div class="row marketing">
        @include('components.error', ['error' => $errors ?? null])
        <form class="form-inline" method="POST" id="wz-doc-edit-form"
              action="{{ $newPage ? wzRoute('project:doc:new:show', ['id' => $project->id]) : wzRoute('project:doc:edit:show', ['id' => $project->id, 'page_id' => $pageItem->id]) }}">

            @include('components.doc-edit', ['project' => $project, 'pageItem' => $pageItem ?? null, 'navigator' => $navigator])
            <input type="hidden" name="type" value="swagger" />

            <div class="col-lg-12">
                <textarea class="form-control" id="editor-content" name="content" style="width: 100%;" placeholder="符合Swagger规范的文档内容" rows="30">{{ $pageItem->content or '' }}</textarea>
            </div>
        </form>
    </div>
@endsection
